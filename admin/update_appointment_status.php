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

        // Fetch appointment to check severity and current details
        $fetchSql = "SELECT severity, firstname, phone FROM appointments WHERE id = ?";
        $fetchStmt = mysqli_prepare($conn, $fetchSql);
        mysqli_stmt_bind_param($fetchStmt, "i", $id);
        mysqli_stmt_execute($fetchStmt);
        $result = mysqli_stmt_get_result($fetchStmt);
        $appointment = mysqli_fetch_assoc($result);
        if (!$appointment) {
            throw new Exception('Appointment not found');
        }

        // Prevent rescheduling for urgent appointments
        if ($status === 'Rescheduled' && isset($appointment['severity']) && $appointment['severity'] === 'Urgent') {
            throw new Exception('Urgent appointments cannot be rescheduled');
        }

        // Update appointment status
        $sql = "UPDATE appointments SET status = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $status, $id);
        mysqli_stmt_execute($stmt);

        // If rescheduling, update the date and time
        if ($status === 'Rescheduled' && $new_date && $new_time) {
            // Ensure correct column name for date field
            $sql = "UPDATE appointments SET appointment_date = ?, time_slot = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssi", $new_date, $new_time, $id);
            mysqli_stmt_execute($stmt);

            // Send SMS notification to the user about the reschedule
            require_once __DIR__ . '/../client/utils/sms_sender.php';
            require_once __DIR__ . '/api/api_key.php';
            $smsResult = sendRescheduledSMS($api_key, $sender_name, $appointment['phone'], $appointment['firstname'], $new_date, $new_time);
            if (!$smsResult['success']) {
                // Do not fail the whole operation; include warning in response
                $smsWarning = $smsResult['error'] ?? 'Unknown SMS error';
            }
        }

        // If cancelled, log the reason and send SMS
        if ($status === 'Cancelled') {
            if ($reason) {
                $sql = "INSERT INTO appointment_logs (appointment_id, action, reason) VALUES (?, 'Cancelled', ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "is", $id, $reason);
                mysqli_stmt_execute($stmt);
            }

            // Send SMS notification about cancellation
            require_once __DIR__ . '/../client/utils/sms_sender.php';
            require_once __DIR__ . '/api/api_key.php';
            $smsCancelResult = sendCanceledSMS($api_key, $sender_name, $appointment['phone'], $appointment['firstname']);
            if (!$smsCancelResult['success']) {
                $smsWarning = ($smsWarning ?? '') . ' | Cancel SMS: ' . ($smsCancelResult['error'] ?? 'Unknown SMS error');
            }
        }

        // Commit transaction
        mysqli_commit($conn);
        $response = ['success' => true, 'message' => 'Appointment status updated successfully'];
        if (isset($smsWarning)) {
            $response['sms_warning'] = $smsWarning;
        }
        echo json_encode($response);

    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        echo json_encode(['success' => false, 'message' => 'Error updating appointment: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>