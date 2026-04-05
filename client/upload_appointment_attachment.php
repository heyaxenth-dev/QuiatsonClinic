<?php
/**
 * Client: replace Senior/PWD ID file for an existing appointment (JSON).
 */
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['client_auth']) || $_SESSION['client_auth'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Please login.']);
    exit;
}

include '../database/conn.php';

$user_id = (int) ($_SESSION['user_id'] ?? 0);

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

$uploaded = $_FILES['upload_id'] ?? null;
if (!$uploaded || !isset($uploaded['error']) || $uploaded['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Please choose a valid file to upload.']);
    exit;
}

$stmt = $conn->prepare("SELECT id, status, patient_type, uploaded_id FROM appointments WHERE id = ? AND created_by = ? LIMIT 1");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error.']);
    exit;
}
$stmt->bind_param("ii", $appointment_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    $stmt->close();
    echo json_encode(['success' => false, 'message' => 'Appointment not found.']);
    exit;
}
$row = $result->fetch_assoc();
$stmt->close();

if (in_array($row['status'], ['Cancelled', 'Concluded'], true)) {
    echo json_encode(['success' => false, 'message' => 'This appointment cannot be updated.']);
    exit;
}

$ptype = strtolower((string) ($row['patient_type'] ?? ''));
if (!in_array($ptype, ['senior', 'senior_pwd'], true)) {
    echo json_encode(['success' => false, 'message' => 'ID upload applies only to Senior / PWD appointments.']);
    exit;
}

$allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];
$max_bytes = 5 * 1024 * 1024;
$file_extension = strtolower(pathinfo($uploaded['name'], PATHINFO_EXTENSION));
$file_size = (int) $uploaded['size'];

if (!in_array($file_extension, $allowed_extensions, true)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Use JPG, PNG, or PDF.']);
    exit;
}

if ($file_size <= 0 || $file_size > $max_bytes) {
    echo json_encode(['success' => false, 'message' => 'File must be between 1 byte and 5 MB.']);
    exit;
}

$target_dir = __DIR__ . '/uploads/uploaded_ids/';
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
}

$safe_ext = preg_replace('/[^a-z0-9]+/i', '', $file_extension);
$new_relative = 'uploads/uploaded_ids/' . uniqid('uploaded_id_', true) . '.' . $safe_ext;
$target_file = __DIR__ . '/' . $new_relative;

$moved = false;
if (is_uploaded_file($uploaded['tmp_name'])) {
    $moved = move_uploaded_file($uploaded['tmp_name'], $target_file);
}
if (!$moved) {
    $moved = @rename($uploaded['tmp_name'], $target_file);
}
if (!$moved) {
    echo json_encode(['success' => false, 'message' => 'Could not save the file. Please try again.']);
    exit;
}

// Remove previous file if it was stored under our uploads folder
$old = $row['uploaded_id'] ?? '';
if ($old !== '' && strpos($old, '..') === false) {
    $base = realpath(__DIR__ . '/uploads/uploaded_ids');
    $full = realpath(__DIR__ . '/' . $old);
    if ($base && $full && strncmp($full, $base, strlen($base)) === 0 && is_file($full)) {
        @unlink($full);
    }
}

$upd = $conn->prepare("UPDATE appointments SET uploaded_id = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND created_by = ?");
if (!$upd) {
    @unlink($target_file);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error.']);
    exit;
}
$upd->bind_param("sii", $new_relative, $appointment_id, $user_id);
if ($upd->execute()) {
    echo json_encode(['success' => true, 'message' => 'Attachment updated successfully.']);
} else {
    @unlink($target_file);
    echo json_encode(['success' => false, 'message' => 'Failed to update appointment record.']);
}
$upd->close();
$conn->close();
