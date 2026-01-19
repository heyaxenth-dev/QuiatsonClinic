<?php
session_start();
header('Content-Type: application/json');

include '../database/conn.php';
include 'authentication.php';
checkLogin();

$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$appointment_id = isset($_POST['appointment_id']) ? (int) $_POST['appointment_id'] : 0;
$new_date = isset($_POST['date']) ? trim($_POST['date']) : '';
$new_time_slot = isset($_POST['time_slot']) ? trim($_POST['time_slot']) : '';

if ($appointment_id <= 0 || empty($new_date) || empty($new_time_slot)) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Basic date validation
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $new_date)) {
    echo json_encode(['success' => false, 'message' => 'Invalid date format']);
    exit;
}

// Ensure new date is today or in the future
$today = date('Y-m-d');
if ($new_date < $today) {
    echo json_encode(['success' => false, 'message' => 'You cannot reschedule to a past date']);
    exit;
}

// Verify appointment belongs to current user and is still active
$stmt = $conn->prepare("SELECT id, status FROM appointments WHERE id = ? AND created_by = ? LIMIT 1");
$stmt->bind_param("ii", $appointment_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    echo json_encode(['success' => false, 'message' => 'Appointment not found']);
    exit;
}

$appointment = $result->fetch_assoc();
$stmt->close();

if (in_array($appointment['status'], ['Cancelled', 'Concluded'], true)) {
    echo json_encode(['success' => false, 'message' => 'This appointment can no longer be rescheduled']);
    exit;
}

// Check if date is marked as unavailable by staff
$unavailableStmt = $conn->prepare("SELECT COUNT(*) as count FROM staff_schedules WHERE schedule_date = ? AND is_unavailable = 1");
$unavailableStmt->bind_param("s", $new_date);
$unavailableStmt->execute();
$unavailableResult = $unavailableStmt->get_result();
$unavailableRow = $unavailableResult->fetch_assoc();
$unavailableStmt->close();

if (!empty($unavailableRow['count']) && (int)$unavailableRow['count'] > 0) {
    echo json_encode(['success' => false, 'message' => 'Selected date is unavailable. Please choose another date.']);
    exit;
}

// Check capacity for the selected time slot (10 patients per slot)
$check_stmt = $conn->prepare("SELECT COUNT(*) as booked_count FROM appointments WHERE appointment_date = ? AND time_slot = ? AND status != 'Cancelled'");
$check_stmt->bind_param("ss", $new_date, $new_time_slot);
$check_stmt->execute();
$check_result = $check_stmt->get_result();
$booked_count = (int) $check_result->fetch_assoc()['booked_count'];
$check_stmt->close();

if ($booked_count >= 10) {
    echo json_encode([
        'success' => false,
        'message' => 'The selected time slot is already fully booked. Please choose another slot.'
    ]);
    exit;
}

// Perform the update
$update = $conn->prepare("UPDATE appointments SET appointment_date = ?, time_slot = ?, updated_at = CURRENT_TIMESTAMP, status = 'Approved' WHERE id = ? AND created_by = ?");
$update->bind_param("ssii", $new_date, $new_time_slot, $appointment_id, $user_id);

if ($update->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Appointment rescheduled successfully.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to reschedule appointment. Please try again.'
    ]);
}

$update->close();
$conn->close();
