<?php
session_start();
include '../database/conn.php';

header('Content-Type: application/json');

// Ensure user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$userEmail = $_SESSION['email'] ?? null;
$userPhone = $_SESSION['phone'] ?? null;
$userName  = $_SESSION['username'] ?? null;
$userId    = $_SESSION['user_id'] ?? null;

$params = [];
$sql = "SELECT id, firstname, lastname, appointment_date, time_slot, status, symptom 
        FROM appointments";

$where = [];

// Always restrict by created_by first
$where[] = "created_by = ?";
$params[] = $userId;

// Optional filters
if ($userPhone) {
    $where[] = "phone = ?";
    $params[] = $userPhone;
}

if ($userEmail && in_array('email', array_column($conn->query("SHOW COLUMNS FROM appointments")->fetch_all(MYSQLI_ASSOC), 'Field'))) {
    $where[] = "email = ?";
    $params[] = $userEmail;
}

// Apply conditions
if (!empty($where)) {
    $sql .= " WHERE " . implode(' OR ', $where);
} else {
    $sql .= " WHERE appointment_date >= CURDATE()";
}

$sql .= " ORDER BY appointment_date ASC, time_slot ASC";

// Prepare & execute safely
if (!empty($params)) {
    $types = str_repeat('s', count($params));
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}

$events = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $date = $row['appointment_date'];
        $timeRange = $row['time_slot'];
        $times = explode(' - ', $timeRange);
        $startTime = $times[0] ?? '8:30 AM';
        $endTime   = $times[1] ?? $startTime;

        $start = date('c', strtotime($date . ' ' . $startTime));
        $end   = date('c', strtotime($date . ' ' . $endTime));

        $title = trim(($row['firstname'] ?? '') . ' ' . ($row['lastname'] ?? ''));
        if ($title === '') $title = 'Clinic Check-up';
        if (!empty($row['symptom'])) $title .= ' - ' . $row['symptom'];

        $events[] = [
            'id' => (string)$row['id'],
            'title' => $title,
            'start' => $start,
            'end' => $end,
            'extendedProps' => [
                'status' => $row['status'],
                'time_slot' => $row['time_slot'],
            ]
        ];
    }
}

// Add unavailable dates from staff schedules
$unavailableStmt = $conn->prepare("SELECT DISTINCT schedule_date, GROUP_CONCAT(DISTINCT reason SEPARATOR ', ') as reasons FROM staff_schedules WHERE is_unavailable = 1 AND schedule_date >= CURDATE() GROUP BY schedule_date ORDER BY schedule_date ASC");
$unavailableStmt->execute();
$unavailableResult = $unavailableStmt->get_result();

while ($row = $unavailableResult->fetch_assoc()) {
    $date = $row['schedule_date'];
    $reason = !empty($row['reasons']) ? ' - ' . $row['reasons'] : '';
    
    $events[] = [
        'id' => 'unavailable_' . $date,
        'title' => 'Clinic Unavailable' . $reason,
        'start' => $date,
        'allDay' => true,
        'backgroundColor' => '#6c757d',
        'borderColor' => '#5a6268',
        'textColor' => '#ffffff',
        'extendedProps' => [
            'type' => 'unavailable',
            'status' => 'Unavailable',
            'reason' => $row['reasons'] ?? ''
        ]
    ];
}
$unavailableStmt->close();

echo json_encode($events);

if (isset($stmt)) { $stmt->close(); }
$conn->close();