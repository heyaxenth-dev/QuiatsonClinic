<?php
include '../database/conn.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['makeConclusion'])) {
    $id = $_POST['id'];
    $remarks = $_POST['remarks'];
    // Add other fields as needed

    $sql = "UPDATE appointments SET status='Concluded', remarks=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $remarks, $id);
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>