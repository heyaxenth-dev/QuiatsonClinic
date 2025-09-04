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

// Fetch appointments for the current user by email if present in table or by patient identifier fields
// The schema stores patient details inline; match by phone/email if available in session

$userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : null;
$userPhone = isset($_SESSION['phone']) ? $_SESSION['phone'] : null;
$userName = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Build query to fetch all appointments; if there is a user linkage field, prefer it.
// Current schema lacks a foreign key; show all appointments created (no strict link). As a fallback, return all approved and pending appointments for visibility.

$params = [];
$sql = "SELECT id, firstname, lastname, appointment_date, time_slot, status, symptom FROM appointments";

// Optional filters (best effort)
$where = [];
if ($userPhone) {
    $where[] = "phone = ?";
    $params[] = $userPhone;
}
if ($userEmail && in_array('email', array_column($conn->query("SHOW COLUMNS FROM appointments")->fetch_all(MYSQLI_ASSOC), 'Field'))) {
    $where[] = "email = ?";
    $params[] = $userEmail;
}

if (!empty($where)) {
    $sql .= " WHERE " . implode(' OR ', $where);
} else {
    // Default to only show future or today appointments
    $sql .= " WHERE appointment_date >= CURDATE()";
}

$sql .= " ORDER BY appointment_date ASC, time_slot ASC";

// Prepare statement safely depending on params
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
        $timeRange = $row['time_slot']; // e.g., "8:30 AM - 9:30 AM"
        $times = explode(' - ', $timeRange);
        $startTime = isset($times[0]) ? $times[0] : '8:30 AM';
        $endTime = isset($times[1]) ? $times[1] : $startTime;

        // Convert to ISO datetime
        $start = date('c', strtotime($date . ' ' . $startTime));
        $end = date('c', strtotime($date . ' ' . $endTime));

        $title = trim(($row['firstname'] ?? '') . ' ' . ($row['lastname'] ?? ''));
        if ($title === '') {
            $title = 'Clinic Check-up';
        }
        if (!empty($row['symptom'])) {
            $title .= ' - ' . $row['symptom'];
        }

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

echo json_encode($events);

if (isset($stmt)) { $stmt->close(); }
$conn->close();

?>