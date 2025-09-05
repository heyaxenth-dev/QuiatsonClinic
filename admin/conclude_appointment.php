<?php
include '../database/conn.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$id = $_POST['id'] ?? null;
$makeConclusion = $_POST['makeConclusion'] ?? null;
if (!$id || !$makeConclusion) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

// Build a detailed remark using provided form fields
$patient_name = $_POST['patient_name'] ?? '';
$age = $_POST['age'] ?? '';
$sex = $_POST['sex'] ?? '';
$address = $_POST['address'] ?? '';
$civil_status = $_POST['civil_status'] ?? '';
$phone = $_POST['phone'] ?? '';
$status = $_POST['status'] ?? '';
$day = $_POST['day'] ?? '';
$date = $_POST['date'] ?? '';
$time = $_POST['time'] ?? '';
$checkup_type = $_POST['checkup_type'] ?? '';
$lab_type = $_POST['lab_type'] ?? '';
$remarks_input = $_POST['remarks'] ?? '';

$compiledRemarks = trim(
    "Patient: {$patient_name}\n" .
    (strlen($age) ? "Age: {$age}\n" : '') .
    (strlen($sex) ? "Sex: {$sex}\n" : '') .
    (strlen($address) ? "Address: {$address}\n" : '') .
    (strlen($civil_status) ? "Civil Status: {$civil_status}\n" : '') .
    (strlen($phone) ? "Phone: {$phone}\n" : '') .
    (strlen($status) ? "Current Status: {$status}\n" : '') .
    (strlen($day) || strlen($date) || strlen($time) ? "Schedule: {$day} {$date} {$time}\n" : '') .
    (strlen($checkup_type) ? "Checkup Type: {$checkup_type}\n" : '') .
    (strlen($lab_type) ? "Lab Type: {$lab_type}\n" : '') .
    (strlen($remarks_input) ? "Remarks: {$remarks_input}" : '')
);

try {
    // Ensure appointment exists
    $checkSql = "SELECT id FROM appointments WHERE id = ?";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    mysqli_stmt_bind_param($checkStmt, 'i', $id);
    mysqli_stmt_execute($checkStmt);
    $res = mysqli_stmt_get_result($checkStmt);
    if (!mysqli_fetch_assoc($res)) {
        echo json_encode(['success' => false, 'message' => 'Appointment not found']);
        exit;
    }

    // Update to Concluded and save compiled remarks
    $sql = "UPDATE appointments SET status = 'Concluded', remarks = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $compiledRemarks, $id);
    $ok = mysqli_stmt_execute($stmt);

    if (!$ok) {
        echo json_encode(['success' => false, 'message' => 'Database error while concluding appointment']);
        exit;
    }

    echo json_encode(['success' => true, 'message' => 'Appointment concluded successfully']);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => 'Unexpected error: ' . $e->getMessage()]);
}
?>