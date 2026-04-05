<?php
// Start output buffering to prevent any unwanted output
ob_start();
header('Content-Type: application/json');

// Check authentication without redirecting (for API endpoints)
session_start();
if (!isset($_SESSION['client_auth']) || $_SESSION['client_auth'] !== true) {
    ob_end_clean();
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Please login.']);
    exit;
}

include '../database/conn.php';

$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ob_end_clean();
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$appointment_id = isset($_POST['appointment_id']) ? (int) $_POST['appointment_id'] : 0;
$new_date = isset($_POST['date']) ? trim($_POST['date']) : '';
$new_time_slot = isset($_POST['time_slot']) ? trim($_POST['time_slot']) : '';

if ($appointment_id <= 0 || empty($new_date) || empty($new_time_slot)) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Basic date validation
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $new_date)) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Invalid date format']);
    exit;
}

// Ensure new date is today or in the future
$today = date('Y-m-d');
if ($new_date < $today) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'You cannot reschedule to a past date']);
    exit;
}

// Verify appointment belongs to current user and is still active
$stmt = $conn->prepare("SELECT id, status FROM appointments WHERE id = ? AND created_by = ? LIMIT 1");
if (!$stmt) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error (verify appointment): ' . $conn->error]);
    exit;
}
$stmt->bind_param("ii", $appointment_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Appointment not found']);
    exit;
}

$appointment = $result->fetch_assoc();
$stmt->close();

if (in_array($appointment['status'], ['Cancelled', 'Concluded'], true)) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'This appointment can no longer be rescheduled']);
    exit;
}

// Check if date is marked as unavailable by staff
$unavailableStmt = $conn->prepare("SELECT COUNT(*) as count FROM staff_schedules WHERE schedule_date = ? AND is_unavailable = 1");
if (!$unavailableStmt) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error (staff schedules): ' . $conn->error]);
    exit;
}
$unavailableStmt->bind_param("s", $new_date);
$unavailableStmt->execute();
$unavailableResult = $unavailableStmt->get_result();
$unavailableRow = $unavailableResult->fetch_assoc();
$unavailableStmt->close();

if (!empty($unavailableRow['count']) && (int)$unavailableRow['count'] > 0) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Selected date is unavailable. Please choose another date.']);
    exit;
}

// Check capacity for the selected time slot (10 patients per slot; exclude this appointment from the count)
$check_stmt = $conn->prepare("SELECT COUNT(*) as booked_count FROM appointments WHERE appointment_date = ? AND time_slot = ? AND status != 'Cancelled' AND id != ?");
if (!$check_stmt) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error (slot capacity): ' . $conn->error]);
    exit;
}
$check_stmt->bind_param("ssi", $new_date, $new_time_slot, $appointment_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();
$booked_count = (int) $check_result->fetch_assoc()['booked_count'];
$check_stmt->close();

if ($booked_count >= 10) {
    ob_end_clean();
    echo json_encode([
        'success' => false,
        'message' => 'The selected time slot is already fully booked. Please choose another slot.'
    ]);
    exit;
}

// Perform the update
$update = $conn->prepare("UPDATE appointments SET appointment_date = ?, time_slot = ?, updated_at = CURRENT_TIMESTAMP, status = 'Approved' WHERE id = ? AND created_by = ?");
if (!$update) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error (update appointment): ' . $conn->error]);
    exit;
}

$update->bind_param("ssii", $new_date, $new_time_slot, $appointment_id, $user_id);

if ($update->execute()) {
    ob_end_clean();
    echo json_encode([
        'success' => true,
        'message' => 'Appointment rescheduled successfully.'
    ]);
} else {
    ob_end_clean();
    echo json_encode([
        'success' => false,
        'message' => 'Failed to reschedule appointment. Please try again. ' . $update->error
    ]);
}

$update->close();
$conn->close();