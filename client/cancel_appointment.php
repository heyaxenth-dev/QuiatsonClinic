<?php
ob_start();
header('Content-Type: application/json');

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

if ($appointment_id <= 0) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Invalid appointment']);
    exit;
}

$stmt = $conn->prepare("SELECT id, status FROM appointments WHERE id = ? AND created_by = ? LIMIT 1");
if (!$stmt) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error.']);
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

if ($appointment['status'] === 'Concluded') {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Concluded appointments cannot be cancelled']);
    exit;
}

if ($appointment['status'] === 'Cancelled') {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'This appointment is already cancelled']);
    exit;
}

$update = $conn->prepare("UPDATE appointments SET status = 'Cancelled', updated_at = CURRENT_TIMESTAMP WHERE id = ? AND created_by = ?");
$update->bind_param("ii", $appointment_id, $user_id);

if ($update->execute()) {
    ob_end_clean();
    echo json_encode([
        'success' => true,
        'message' => 'Appointment cancelled successfully.'
    ]);
} else {
    ob_end_clean();
    echo json_encode([
        'success' => false,
        'message' => 'Failed to cancel appointment. Please try again.'
    ]);
}

$update->close();
$conn->close();
