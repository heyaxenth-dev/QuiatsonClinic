<?php
include "../database/conn.php";

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    
    // Query to fetch youth details
    $sql = "SELECT * FROM appointments WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        
        // Format the data for JSON response
        $response = array(
            'id' => $data['id'],
            'severity' => $data['severity'],
            'lastname' => $data['lastname'],
            'firstname' => $data['firstname'],
            'middle_initial' => $data['middle_initial'],
            'address' => $data['address'],
            'age' => $data['age'],
            'sex' => $data['sex'],
            'birthdate' => $data['birthdate'],
            'civil_status' => $data['civil_status'],
            'phone' => $data['phone'],
            'weight' => $data['weight'],
            'height' => $data['height'],
            'bloodtype' => $data['bloodtype'],
            'symptoms' => $data['symptom'],
            'date' => $data['appointment_date'],
            'time_slot' => $data['time_slot']
        );
        
        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // No data found
        header('HTTP/1.1 404 Not Found');
        echo json_encode(array('error' => 'Patient record not found'));
    }
} else {
    // No ID provided
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('error' => 'No ID provided'));
}

$stmt->close();
$conn->close();
?>