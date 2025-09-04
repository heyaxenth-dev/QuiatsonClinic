<?php
// Test SMS functionality
include 'utils/sms_sender.php';
include './admin/api/api_key.php';

echo "<h2>SMS Connection Test</h2>";

// Test 1: Check if we can reach Semaphore API
echo "<h3>1. Testing Semaphore API Connection</h3>";
$connection_test = testSemaphoreConnection();
echo "Reachable: " . ($connection_test['reachable'] ? 'YES' : 'NO') . "<br>";
echo "HTTP Code: " . $connection_test['http_code'] . "<br>";
echo "Error: " . ($connection_test['error'] ?: 'None') . "<br>";
echo "Response: " . htmlspecialchars($connection_test['response']) . "<br><br>";

// Test 2: Test phone number formatting
echo "<h3>2. Testing Phone Number Formatting</h3>";
$test_phones = ['09123456789', '+639123456789', '639123456789', '9123456789'];
foreach ($test_phones as $phone) {
    $formatted = formatPhoneNumber($phone);
    echo "Original: $phone -> Formatted: $formatted<br>";
}
echo "<br>";

// Test 3: Test SMS sending (with a test number - replace with your own)
echo "<h3>3. Testing SMS Sending</h3>";
echo "API Key: " . substr($api_key, 0, 10) . "...<br>";
echo "Sender Name: $sender_name<br>";

// Uncomment the line below to test actual SMS sending
// $result = sendSMS($api_key, $sender_name, '09123456789', 'Test User', '2024-01-01', '10:00 AM', 'TEST123');
// echo "SMS Result: " . print_r($result, true);

echo "<br><strong>Note:</strong> Uncomment the SMS test line above to test actual SMS sending.";
?>