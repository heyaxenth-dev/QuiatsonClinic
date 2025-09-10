<?php
require_once __DIR__ . '/database/conn.php';

header('Content-Type: application/json');

// Get the field and value from POST data
$field = isset($_POST['field']) ? trim($_POST['field']) : '';
$value = isset($_POST['value']) ? trim($_POST['value']) : '';

// Validate input
if (empty($field) || empty($value)) {
    echo json_encode([
        'duplicate' => false,
        'fields' => [],
        'error' => 'Invalid input'
    ]);
    exit;
}

// Allowed fields to check
$allowedFields = ['username', 'email', 'mobile_no'];
if (!in_array($field, $allowedFields)) {
    echo json_encode([
        'duplicate' => false,
        'fields' => [],
        'error' => 'Invalid field'
    ]);
    exit;
}

$duplicateFields = [];

try {
    // Check for duplicates in admin_staff table
    if ($field === 'username') {
        $stmt = $conn->prepare('SELECT id FROM admin_staff WHERE username = ? LIMIT 1');
    } elseif ($field === 'email') {
        $stmt = $conn->prepare('SELECT id FROM admin_staff WHERE email = ? LIMIT 1');
    } elseif ($field === 'mobile_no') {
        $stmt = $conn->prepare('SELECT id FROM admin_staff WHERE mobile_no = ? LIMIT 1');
    }
    
    $stmt->bind_param('s', $value);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $duplicateFields[] = $field;
    }
    $stmt->close();
    
    $response = [
        'duplicate' => !empty($duplicateFields),
        'fields' => $duplicateFields,
        'error' => null
    ];
    
} catch (Exception $e) {
    $response = [
        'duplicate' => false,
        'fields' => [],
        'error' => 'Database error: ' . $e->getMessage()
    ];
}

echo json_encode($response);
?>