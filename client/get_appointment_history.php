<?php
session_start();
include '../database/conn.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Get the most recent appointment history for the user
    $stmt = $conn->prepare("
        SELECT 
            lastname, firstname, middle_initial, address, age, sex, 
            birthdate, civil_status, phone, weight, height, bloodtype, 
            patient_type, symptom, created_at
        FROM appointment_history 
        WHERE user_id = ? 
        ORDER BY updated_at DESC 
        LIMIT 1
    ");
    
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $history = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'data' => $history
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'data' => null
        ]);
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}

$conn->close();
?>