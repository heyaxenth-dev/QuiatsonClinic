<?php
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

if ($appointment_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid appointment']);
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

if ($appointment['status'] === 'Concluded') {
    echo json_encode(['success' => false, 'message' => 'Concluded appointments cannot be cancelled']);
    exit;
}

if ($appointment['status'] === 'Cancelled') {
    echo json_encode(['success' => false, 'message' => 'This appointment is already cancelled']);
    exit;
}

// Soft delete: mark as Cancelled
$update = $conn->prepare("UPDATE appointments SET status = 'Cancelled', updated_at = CURRENT_TIMESTAMP WHERE id = ? AND created_by = ?");
$update->bind_param("ii", $appointment_id, $user_id);

if ($update->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Appointment cancelled successfully.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to cancel appointment. Please try again.'
    ]);
}

$update->close();
$conn->close();