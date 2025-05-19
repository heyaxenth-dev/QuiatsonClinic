<?php
include '../database/conn.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $status = $_POST['status'] ?? '';
    $new_date = $_POST['new_date'] ?? null;
    $new_time = $_POST['new_time'] ?? null;
    $reason = $_POST['reason'] ?? null;

    if (empty($id) || empty($status)) {
        echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
        exit;
    }

    try {
        // Start transaction
        mysqli_begin_transaction($conn);

        // Update appointment status
        $sql = "UPDATE appointments SET status = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $status, $id);
        mysqli_stmt_execute($stmt);

        // If rescheduling, update the date and time
        if ($status === 'Rescheduled' && $new_date && $new_time) {
            $sql = "UPDATE appointments SET date = ?, time_slot = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssi", $new_date, $new_time, $id);
            mysqli_stmt_execute($stmt);
        }

        // If cancelled, log the reason
        if ($status === 'Cancelled' && $reason) {
            $sql = "INSERT INTO appointment_logs (appointment_id, action, reason) VALUES (?, 'Cancelled', ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "is", $id, $reason);
            mysqli_stmt_execute($stmt);
        }

        // Commit transaction
        mysqli_commit($conn);
        echo json_encode(['success' => true, 'message' => 'Appointment status updated successfully']);

    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        echo json_encode(['success' => false, 'message' => 'Error updating appointment: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>