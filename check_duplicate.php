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
$allowedFields = ['email', 'phone'];
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
    // Check for duplicates in client table
    if ($field === 'email') {
        $stmt = $conn->prepare('SELECT id FROM client WHERE email = ? LIMIT 1');
    } elseif ($field === 'phone') {
        $stmt = $conn->prepare('SELECT id FROM client WHERE mobile_no = ? LIMIT 1');
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