<?php
include '../database/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['date'])) {
    $selected_date = $conn->real_escape_string($_POST['date']);
    
    // Define clinic hours and time slots
    $time_slots = [
        '8:30 AM - 9:30 AM',
        '9:30 AM - 10:30 AM', 
        '10:30 AM - 11:30 AM',
        '11:30 AM - 12:30 PM',
        '1:30 PM - 2:30 PM',
        '2:30 PM - 3:30 PM',
        '3:30 PM - 4:30 PM',
        '4:30 PM - 5:30 PM'
    ];
    
    // Get booked appointments for the selected date
    $stmt = $conn->prepare("SELECT time_slot, COUNT(*) as booked_count FROM appointments WHERE appointment_date = ? GROUP BY time_slot");
    $stmt->bind_param("s", $selected_date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $booked_slots = [];
    while ($row = $result->fetch_assoc()) {
        $booked_slots[$row['time_slot']] = $row['booked_count'];
    }
    
    $available_slots = [];
    foreach ($time_slots as $slot) {
        $booked_count = isset($booked_slots[$slot]) ? $booked_slots[$slot] : 0;
        $available_count = 10 - $booked_count; // 10 patients per hour limit
        
        if ($available_count > 0) {
            $available_slots[] = [
                'time_slot' => $slot,
                'available_count' => $available_count,
                'booked_count' => $booked_count
            ];
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode($available_slots);
    
    $stmt->close();
    $conn->close();
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}
?>