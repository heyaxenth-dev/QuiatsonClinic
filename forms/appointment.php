<?php
session_start();
// Connection config
include '../database/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['makeAppointment'])) {
    // Get POST data safely
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

    // Define urgent symptoms
    $urgent_symptoms = [
        "Chest Pain (Moderate to severe)",
        "Abdominal Pain",
        "Shortness of Breath",
        "Toxic Looking"
    ];

    // Determine severity
    $severity = in_array($symptom, $urgent_symptoms) ? 'Urgent' : 'Regular';

    // Insert patient record without patient_id first
    $stmt = $conn->prepare("INSERT INTO appointments (
        severity, lastname, firstname, middle_initial, address, age, sex, birthdate,
        civil_status, phone, weight, height, bloodtype,
        appointment_date, time_slot, symptom
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "sssssissssssssss",
        $severity, $lastname, $firstname, $middle_initial, $address, $age, $sex, $birthdate,
        $civil_status, $phone, $weight, $height, $bloodtype,
        $appointment_date, $time_slot, $symptom
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
        
        $_SESSION['status'] = "Success";
        $_SESSION['status_text'] = "Appointment Sent Successfully!";
        $_SESSION['status_code'] = "success";
        $_SESSION['status_btn'] = "Ok";
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