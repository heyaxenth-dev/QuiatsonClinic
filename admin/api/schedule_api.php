<?php
header('Content-Type: application/json');
include '../../database/conn.php';

// Check if user is logged in
session_start();
// Check if admin is authenticated
if (!isset($_SESSION['admin_auth']) || $_SESSION['admin_auth'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'add':
        addSchedule($conn);
        break;
    case 'get':
        getSchedules($conn);
        break;
    case 'delete':
        deleteSchedule($conn);
        break;
    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}

function addSchedule($conn) {
    $staff_id = intval($_POST['staff_id'] ?? 0);
    $schedule_date = $conn->real_escape_string($_POST['schedule_date'] ?? '');
    $start_time = !empty($_POST['start_time']) ? $conn->real_escape_string($_POST['start_time']) : null;
    $end_time = !empty($_POST['end_time']) ? $conn->real_escape_string($_POST['end_time']) : null;
    $reason = !empty($_POST['reason']) ? $conn->real_escape_string($_POST['reason']) : null;
    $created_by = intval($_POST['created_by'] ?? $_SESSION['user_id'] ?? 0);

    if (empty($schedule_date)) {
        echo json_encode(['success' => false, 'message' => 'Date is required']);
        return;
    }

    // Check if schedule already exists for this date
    $checkStmt = $conn->prepare("SELECT id FROM staff_schedules WHERE staff_id = ? AND schedule_date = ?");
    $checkStmt->bind_param("is", $staff_id, $schedule_date);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows > 0) {
        $checkStmt->close();
        echo json_encode(['success' => false, 'message' => 'Schedule already exists for this date']);
        return;
    }
    $checkStmt->close();

    // Insert new schedule
    $stmt = $conn->prepare("INSERT INTO staff_schedules (staff_id, schedule_date, start_time, end_time, is_unavailable, reason, created_by) VALUES (?, ?, ?, ?, 1, ?, ?)");
    $stmt->bind_param("issssi", $staff_id, $schedule_date, $start_time, $end_time, $reason, $created_by);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Schedule added successfully', 'id' => $conn->insert_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add schedule: ' . $conn->error]);
    }
    
    $stmt->close();
}

function getSchedules($conn) {
    $staff_id = intval($_GET['staff_id'] ?? 0);
    $year = intval($_GET['year'] ?? 0);
    $month = intval($_GET['month'] ?? 0);

    $query = "SELECT * FROM staff_schedules WHERE staff_id = ? AND is_unavailable = 1";
    $params = [];
    $types = "i";

    if ($year > 0 && $month > 0) {
        $query .= " AND YEAR(schedule_date) = ? AND MONTH(schedule_date) = ?";
        $params[] = $year;
        $params[] = $month;
        $types .= "ii";
    }

    $query .= " ORDER BY schedule_date DESC";

    $stmt = $conn->prepare($query);
    if ($year > 0 && $month > 0) {
        $stmt->bind_param($types, $staff_id, $year, $month);
    } else {
        $stmt->bind_param($types, $staff_id);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $schedules = [];
    while ($row = $result->fetch_assoc()) {
        $schedules[] = $row;
    }
    
    $stmt->close();
    echo json_encode(['success' => true, 'schedules' => $schedules]);
}

function deleteSchedule($conn) {
    $id = intval($_POST['id'] ?? 0);
    $staff_id = $_SESSION['user_id'] ?? 0;

    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid schedule ID']);
        return;
    }

    // Verify the schedule belongs to the current staff member
    $checkStmt = $conn->prepare("SELECT id FROM staff_schedules WHERE id = ? AND staff_id = ?");
    $checkStmt->bind_param("ii", $id, $staff_id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows === 0) {
        $checkStmt->close();
        echo json_encode(['success' => false, 'message' => 'Schedule not found or unauthorized']);
        return;
    }
    $checkStmt->close();

    // Delete the schedule
    $stmt = $conn->prepare("DELETE FROM staff_schedules WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Schedule deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete schedule: ' . $conn->error]);
    }
    
    $stmt->close();
}

$conn->close();
?>

