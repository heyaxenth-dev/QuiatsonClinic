<?php
session_start();
include 'authentication.php';
checkLogin();
include '../database/conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['status'] = 'Error';
    $_SESSION['status_text'] = 'Invalid request method';
    $_SESSION['status_code'] = 'error';
    header('Location: laboratory_result.php');
    exit;
}

$appointment_id = isset($_POST['appointment_id']) ? (int)$_POST['appointment_id'] : 0;
$patient_id = isset($_POST['patient_id']) ? trim($_POST['patient_id']) : '';
$notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';

if ($appointment_id <= 0 || $patient_id === '' || !isset($_FILES['result_file'])) {
    $_SESSION['status'] = 'Error';
    $_SESSION['status_text'] = 'Missing required fields';
    $_SESSION['status_code'] = 'error';
    header('Location: laboratory_result.php');
    exit;
}

// Validate file
$file = $_FILES['result_file'];
if ($file['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['status'] = 'Error';
    $_SESSION['status_text'] = 'File upload error';
    $_SESSION['status_code'] = 'error';
    header('Location: laboratory_result.php');
    exit;
}

$allowed_extensions = ['pdf','png','jpg','jpeg'];
$max_bytes = 10 * 1024 * 1024; // 10MB
$original_name = $file['name'];
$ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
if (!in_array($ext, $allowed_extensions, true)) {
    $_SESSION['status'] = 'Error';
    $_SESSION['status_text'] = 'Invalid file type. Allowed: PDF, PNG, JPG, JPEG';
    $_SESSION['status_code'] = 'error';
    header('Location: laboratory_result.php');
    exit;
}
if ($file['size'] <= 0 || $file['size'] > $max_bytes) {
    $_SESSION['status'] = 'Error';
    $_SESSION['status_text'] = 'File too large. Max 10MB';
    $_SESSION['status_code'] = 'error';
    header('Location: laboratory_result.php');
    exit;
}

// Ensure appointment exists and is concluded
$check = $conn->prepare("SELECT id, status FROM appointments WHERE id = ? AND LOWER(status) = 'concluded'");
$check->bind_param('i', $appointment_id);
$check->execute();
$res = $check->get_result();
if (!$res || $res->num_rows === 0) {
    $_SESSION['status'] = 'Error';
    $_SESSION['status_text'] = 'Appointment not found or not concluded';
    $_SESSION['status_code'] = 'error';
    header('Location: laboratory_result.php');
    exit;
}
$check->close();

// Create directory
$target_dir = '../uploads/lab_results/';
if (!is_dir($target_dir)) {
    @mkdir($target_dir, 0755, true);
}

$safe_ext = preg_replace('/[^a-z0-9]+/i', '', $ext);
$filename = uniqid('lab_result_', true) . '.' . $safe_ext;
$target_path = $target_dir . $filename; // relative from admin -> ../uploads/...

if (!move_uploaded_file($file['tmp_name'], $target_path)) {
    $_SESSION['status'] = 'Error';
    $_SESSION['status_text'] = 'Failed to save file';
    $_SESSION['status_code'] = 'error';
    header('Location: laboratory_result.php');
    exit;
}

// Store path relative to project root for linking from both admin and client
$stored_path = 'uploads/lab_results/' . $filename;

// Upsert into lab_results (unique by appointment_id)
$upsert = $conn->prepare("INSERT INTO lab_results (appointment_id, patient_id, file_path, original_name, notes, uploaded_by) VALUES (?,?,?,?,?,?)
ON DUPLICATE KEY UPDATE file_path = VALUES(file_path), original_name = VALUES(original_name), notes = VALUES(notes), uploaded_by = VALUES(uploaded_by), uploaded_at = CURRENT_TIMESTAMP");

$uploaded_by = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
$upsert->bind_param('issssi', $appointment_id, $patient_id, $stored_path, $original_name, $notes, $uploaded_by);

if ($upsert->execute()) {
    $_SESSION['status'] = 'Success';
    $_SESSION['status_text'] = 'Laboratory result uploaded successfully';
    $_SESSION['status_code'] = 'success';
    $_SESSION['status_btn'] = 'Ok';
} else {
    $_SESSION['status'] = 'Error';
    $_SESSION['status_text'] = 'Database error while saving result';
    $_SESSION['status_code'] = 'error';
}

$upsert->close();

header('Location: laboratory_result.php');
exit;