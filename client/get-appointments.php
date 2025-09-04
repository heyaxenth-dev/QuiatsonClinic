<?php
include 'database/conn.php'; // Include your database connection file

$sql = "SELECT * FROM appointments ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

$events = [];

while ($row = mysqli_fetch_assoc($result)) {
    $title = $row['firstname'] . ' ' . $row['middle_initial'] . '. ' . $row['lastname'] . ' (' . $row['severity'] . ')';

    switch ($row['status']) {
        case 'Pending':     $statusColor = '#f0ad4e'; break;
        case 'Approved':    $statusColor = '#5cb85c'; break;
        case 'Concluded':   $statusColor = '#0275d8'; break;
        case 'Rescheduled': $statusColor = '#5bc0de'; break;
        default:            $statusColor = '#d9534f'; break;
    }

    $events[] = [
        'title' => $title,
        'start' => $row['appointment_date'], // must be ISO date format (YYYY-MM-DD or YYYY-MM-DDTHH:MM:SS)
        'color' => $statusColor
    ];
}

header('Content-Type: application/json');
echo json_encode($events);