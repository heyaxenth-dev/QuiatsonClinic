<?php
header('Content-Type: application/json');

include '../database/conn.php';

// Get dates marked as unavailable by staff
$unavailableStmt = $conn->prepare("SELECT DISTINCT schedule_date FROM staff_schedules WHERE is_unavailable = 1");
$unavailableStmt->execute();
$unavailableResult = $unavailableStmt->get_result();

$unavailableDates = [];
while ($row = $unavailableResult->fetch_assoc()) {
    $unavailableDates[] = $row['schedule_date'];
}
$unavailableStmt->close();

// Define slots and max per slot
$totalSlots = 10 * 4; // 4 slots per day × 10 patients each = 40 per day (client side has 4 slots)

$sql = "SELECT appointment_date, COUNT(*) as total_bookings 
        FROM appointments 
        GROUP BY appointment_date 
        HAVING total_bookings >= $totalSlots";

$result = $conn->query($sql);

$fullyBookedDates = [];
while ($row = $result->fetch_assoc()) {
    $fullyBookedDates[] = $row['appointment_date'];
}

// Merge unavailable dates and fully booked dates
$allUnavailableDates = array_unique(array_merge($unavailableDates, $fullyBookedDates));
sort($allUnavailableDates);

echo json_encode(array_values($allUnavailableDates));
$conn->close();
?>