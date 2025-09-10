<?php
session_start();
// Connection config
include '../database/conn.php';
include '../admin/api/api_key.php';
include 'utils/sms_sender.php'; // Include the SMS sender utility

require_once( 'vendor/autoload.php' );

// Function to save appointment history for future reuse
function saveAppointmentHistory($conn, $user_id, $lastname, $firstname, $middle_initial, 
                               $address, $age, $sex, $birthdate, $civil_status, $phone, 
                               $weight, $height, $bloodtype, $patient_type, $symptom) {
    try {
        // Check if history already exists for this user
        $check_stmt = $conn->prepare("SELECT id FROM appointment_history WHERE user_id = ?");
        $check_stmt->bind_param("i", $user_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $check_stmt->close();
        
        if ($check_result->num_rows > 0) {
            // Update existing history
            $update_stmt = $conn->prepare("
                UPDATE appointment_history SET 
                    lastname = ?, firstname = ?, middle_initial = ?, address = ?, 
                    age = ?, sex = ?, birthdate = ?, civil_status = ?, phone = ?, 
                    weight = ?, height = ?, bloodtype = ?, patient_type = ?, symptom = ?,
                    updated_at = CURRENT_TIMESTAMP
                WHERE user_id = ?
            ");
            $update_stmt->bind_param("ssssisssssssssi", 
                $lastname, $firstname, $middle_initial, $address, $age, $sex, 
                $birthdate, $civil_status, $phone, $weight, $height, $bloodtype, 
                $patient_type, $symptom, $user_id);
            $update_stmt->execute();
            $update_stmt->close();
        } else {
            // Insert new history record
            $insert_stmt = $conn->prepare("
                INSERT INTO appointment_history 
                (user_id, lastname, firstname, middle_initial, address, age, sex, 
                 birthdate, civil_status, phone, weight, height, bloodtype, patient_type, symptom)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $insert_stmt->bind_param("isssissssssssss", 
                $user_id, $lastname, $firstname, $middle_initial, $address, $age, $sex, 
                $birthdate, $civil_status, $phone, $weight, $height, $bloodtype, 
                $patient_type, $symptom);
            $insert_stmt->execute();
            $insert_stmt->close();
        }
    } catch (Exception $e) {
        // Log error but don't interrupt the appointment process
        error_log("Failed to save appointment history: " . $e->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['makeAppointment'])) {
    // Get POST data safely
    $created_by = $conn->real_escape_string($_POST['user_id']); // ID of the user creating the appointment
    $patient_type = $conn->real_escape_string($_POST['patient_type']); // 'regular' or 'senior'/'senior_pwd'
    $uploaded_id = $_FILES['upload_id'] ?? null; // Uploaded file for senior ID, if any
    $lastname = $conn->real_escape_string($_POST['lastname']);
    $firstname = $conn->real_escape_string($_POST['firstname']);
    $middle_initial = $conn->real_escape_string($_POST['middle_initial']);
    // $middle_initial = strtoupper($middle_initial); // Ensure uppercase
    $address = $conn->real_escape_string($_POST['address']);
    $age = $conn->real_escape_string($_POST['age']);
    $sex = $conn->real_escape_string($_POST['sex']);
    $birthdate = $conn->real_escape_string($_POST['birthdate']);
    $civil_status = $conn->real_escape_string($_POST['civil_status']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $weight = $conn->real_escape_string($_POST['weight']);
    $height = $conn->real_escape_string($_POST['height']);
    $bloodtype = $conn->real_escape_string($_POST['bloodtype']);
    $appointment_date = $conn->real_escape_string($_POST['date']);
    $time_slot = $conn->real_escape_string($_POST['time_slot']);
    $symptom = $conn->real_escape_string($_POST['symptom']);

    $sendDate = date("F j, Y", strtotime($_POST['date'])); // Example: January 28, 2025
    
    // Initialize upload target path (empty when not provided)
    $target_file = '';

    // Check if the selected time slot is still available (prevent double booking)
    $check_stmt = $conn->prepare("SELECT COUNT(*) as booked_count FROM appointments WHERE appointment_date = ? AND time_slot = ?");
    $check_stmt->bind_param("ss", $sendDate, $time_slot);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $booked_count = $check_result->fetch_assoc()['booked_count'];
    $check_stmt->close();

    if ($booked_count >= 10) {
        $_SESSION['status'] = "Error";
        $_SESSION['status_text'] = "Sorry, this time slot is now fully booked. Please select another time.";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_btn'] = "Back";
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    }

    // Require ID upload for both 'senior' and 'senior_pwd'
    $is_senior = ($patient_type === 'senior' || $patient_type === 'senior_pwd');
    if ($is_senior) {
        // Validate presence
        if (!$uploaded_id || !isset($uploaded_id['error']) || $uploaded_id['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['status'] = "Error";
            $_SESSION['status_text'] = "Please upload a valid Senior Citizen / PWD ID.";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_btn'] = "Back";
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit();
        }

        // Validate type and size
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];
        $max_bytes = 5 * 1024 * 1024; // 5 MB
        $original_name = $uploaded_id['name'];
        $file_extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
        $file_size = (int) $uploaded_id['size'];

        if (!in_array($file_extension, $allowed_extensions, true)) {
            $_SESSION['status'] = "Error";
            $_SESSION['status_text'] = "Invalid file type. Allowed types: JPG, JPEG, PNG, PDF.";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_btn'] = "Back";
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit();
        }

        if ($file_size <= 0 || $file_size > $max_bytes) {
            $_SESSION['status'] = "Error";
            $_SESSION['status_text'] = "File too large. Maximum size is 5MB.";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_btn'] = "Back";
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit();
        }

        // Ensure target directory exists
        $target_dir = "./uploads/uploaded_ids/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        // Generate safe unique filename
        $safe_ext = preg_replace('/[^a-z0-9]+/i', '', $file_extension);
        $target_file = "uploads/uploaded_ids/" . uniqid('uploaded_id_', true) . '.' . $safe_ext;

        // Use move_uploaded_file when available, fallback to rename for some Windows/PHP configs
        $moved = false;
        if (is_uploaded_file($uploaded_id['tmp_name'])) {
            $moved = move_uploaded_file($uploaded_id['tmp_name'], $target_file);
        }
        if (!$moved) {
            $moved = @rename($uploaded_id['tmp_name'], $target_file);
        }
        if (!$moved) {
            $_SESSION['status'] = "Error";
            $_SESSION['status_text'] = "Failed to upload Senior Citizen / PWD ID. Please try again.";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_btn'] = "Back";
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit();
        }
    }

    // Define urgent symptoms
    $urgent_symptoms = [
        "Chest Pain",
        "Abdominal Pain",
        "Shortness of Breath",
        "Toxic Looking"
    ];

    $default_status = "Approved";

    // Determine severity
    $severity = in_array($symptom, $urgent_symptoms) ? 'Urgent' : 'Regular';

    // Insert patient record without patient_id first
    $stmt = $conn->prepare("INSERT INTO appointments (created_by, patient_type,
        severity, lastname, firstname, middle_initial, address, age, sex, birthdate,
        civil_status, phone, weight, height, bloodtype,
        appointment_date, time_slot, symptom, uploaded_id, status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "issssssissssssssssss", $created_by, $patient_type,
        $severity, $lastname, $firstname, $middle_initial, $address, $age, $sex, $birthdate,
        $civil_status, $phone, $weight, $height, $bloodtype,
        $appointment_date, $time_slot, $symptom, $target_file, $default_status
    );

    if ($stmt->execute()) {
        $insert_id = $conn->insert_id;
        $current_date = date("mdY"); // MMDDYYYY format
        $patient_id = $current_date . '-' . str_pad($insert_id, 2, '0', STR_PAD_LEFT); // Format: 05012025-01

        // Update the patient_id in the same record
        $update = $conn->prepare("UPDATE appointments SET patient_id = ? WHERE id = ?");
        $update->bind_param("si", $patient_id, $insert_id);
        $update->execute();
        $update->close();

        // Save appointment data to history for future reuse
        saveAppointmentHistory($conn, $created_by, $lastname, $firstname, $middle_initial, 
                             $address, $age, $sex, $birthdate, $civil_status, $phone, 
                             $weight, $height, $bloodtype, $patient_type, $symptom);

        // Send SMS notification
        $sms_result = sendSMS($api_key, $sender_name, $phone, $firstname, $sendDate, $time_slot);
        
        if ($sms_result['success']) {
            $_SESSION['status'] = "Success";
            $_SESSION['status_text'] = "Appointment created and SMS notification sent successfully!";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_btn'] = "Ok";
        } else {
            $_SESSION['status'] = "Warning";
            $_SESSION['status_text'] = "Appointment created but SMS notification failed: " . $sms_result['error'];
            $_SESSION['status_code'] = "warning";
            $_SESSION['status_btn'] = "Ok";
        }
        
        header("Location: {$_SERVER['HTTP_REFERER']}");
    } else {
      $_SESSION['status'] = "Error";
      $_SESSION['status_text'] = "Error: " . $sql . "<br>" . $conn->error;
      $_SESSION['status_code'] = "error";
      $_SESSION['status_btn'] = "Back";
      header("Location: {$_SERVER['HTTP_REFERER']}");
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method or missing parameters.";
}
?>