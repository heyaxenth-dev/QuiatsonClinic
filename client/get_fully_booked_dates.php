<?php
header('Content-Type: application/json');

include '../database/conn.php';

// Define slots and max per slot
$totalSlots = 10 * 10; // 10 slots per day × 10 patients each = 100 per day

$sql = "SELECT appointment_date, COUNT(*) as total_bookings 
        FROM appointments 
        GROUP BY appointment_date 
        HAVING total_bookings >= $totalSlots";

$result = $conn->query($sql);

$fullyBookedDates = [];
while ($row = $result->fetch_assoc()) {
    $fullyBookedDates[] = $row['date'];
}

echo json_encode($fullyBookedDates);