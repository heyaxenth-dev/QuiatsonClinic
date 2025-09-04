<?php 

function sendSMS($api_key, $sender_name, $phone, $firstname, $sendDate, $time_slot) {
    // First, test if we can reach the Semaphore API
    $connection_test = testSemaphoreConnection();
    if (!$connection_test['reachable']) {
        return array(
            'success' => false,
            'error' => 'Cannot reach Semaphore API. Network issue: ' . $connection_test['error']
        );
    }
    
    // SMS notification using Semaphore API
    $ch = curl_init();
    
    // Format phone number for Philippines
    $formatted_phone = formatPhoneNumber($phone);
    
    $message = "Hello $firstname,\n\nWe are pleased to inform you that your appointment request has been set!\n\n"
             . "Appointment Date: $sendDate\n"
             . "Time: $time_slot\n"
             . "Location: Quiatson Clinic\n\n"
             . "Please arrive 15 minutes early and bring any necessary documents. If you need to reschedule or have any questions, please contact us through your website account.\n\n"
             . "Thank you, and we look forward to seeing you!";

    $parameters = array(
        'apikey' => $api_key,  // Replace with your Semaphore API key
        'number' => $formatted_phone,  // Patient's contact number from the database
        'message' => $message,
        'sendername' => $sender_name  // Optional: customize sender name
    );

    curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Add this for debugging
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Add this for debugging
    curl_setopt($ch, CURLOPT_TIMEOUT, 60); // Increase timeout to 60 seconds
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); // Connection timeout
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'); // Add user agent
    
    $output = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    // Debug: Log the response (you can remove this later)
    error_log("SMS API Response: " . $output);
    error_log("HTTP Code: " . $http_code);
    error_log("CURL Error: " . $curl_error);

    // Check if the request was successful
    if ($curl_error) {
        return array(
            'success' => false,
            'error' => 'CURL Error: ' . $curl_error
        );
    }

    if ($http_code !== 200) {
        return array(
            'success' => false,
            'error' => 'HTTP Error: ' . $http_code . ' - ' . $output
        );
    }

    // Try to parse the response
    $response_data = json_decode($output, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        return array(
            'success' => false,
            'error' => 'Invalid JSON response: ' . $output
        );
    }

    // Check if SMS was sent successfully based on Semaphore API response
    if (isset($response_data[0]['message_id']) || (isset($response_data['message_id']))) {
        return array(
            'success' => true,
            'message_id' => isset($response_data[0]['message_id']) ? $response_data[0]['message_id'] : $response_data['message_id'],
            'message' => 'SMS sent successfully'
        );
    } else {
        return array(
            'success' => false,
            'error' => 'SMS failed to send. Response: ' . $output
        );
    }
}

function formatPhoneNumber($phone) {
    // Remove any non-numeric characters
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    // If phone starts with 0, replace with 63 (without +)
    if (substr($phone, 0, 1) === '0') {
        $phone = '63' . substr($phone, 1);
    }
    // If phone doesn't start with 63, add 63
    elseif (substr($phone, 0, 2) !== '63') {
        $phone = '63' . $phone;
    }
    
    return $phone;
}

function testSemaphoreConnection() {
    // Test if we can reach the Semaphore API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/account');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    
    $output = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);
    
    return array(
        'reachable' => !$curl_error && $http_code > 0,
        'http_code' => $http_code,
        'error' => $curl_error,
        'response' => $output
    );
}

function sendRescheduledSMS($api_key, $sender_name, $phone, $firstname, $newDate, $newTime) {
    $connection_test = testSemaphoreConnection();
    if (!$connection_test['reachable']) {
        return array(
            'success' => false,
            'error' => 'Cannot reach Semaphore API. Network issue: ' . $connection_test['error']
        );
    }

    $ch = curl_init();
    $formatted_phone = formatPhoneNumber($phone);

    $message = "Hello $firstname,\n\nYour appointment has been successfully RESCHEDULED.\n\n"
             . "New Appointment Date: $newDate\n"
             . "New Time: $newTime\n"
             . "Location: Quiatson Clinic\n\n"
             . "Please arrive 15 minutes early. If you have further changes or questions, kindly contact us through your website account.\n\n"
             . "Thank you for your understanding.";

    $parameters = array(
        'apikey' => $api_key,
        'number' => $formatted_phone,
        'message' => $message,
        'sendername' => $sender_name
    );

    curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

    $output = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    error_log("Rescheduled SMS API Response: " . $output);

    if ($curl_error) return array('success' => false, 'error' => 'CURL Error: ' . $curl_error);
    if ($http_code !== 200) return array('success' => false, 'error' => 'HTTP Error: ' . $http_code . ' - ' . $output);

    $response_data = json_decode($output, true);
    if (json_last_error() !== JSON_ERROR_NONE) return array('success' => false, 'error' => 'Invalid JSON response: ' . $output);

    if (isset($response_data[0]['message_id']) || isset($response_data['message_id'])) {
        return array('success' => true, 'message_id' => $response_data[0]['message_id'] ?? $response_data['message_id'], 'message' => 'Rescheduled SMS sent successfully');
    }
    return array('success' => false, 'error' => 'SMS failed to send. Response: ' . $output);
}

function sendCanceledSMS($api_key, $sender_name, $phone, $firstname) {
    $connection_test = testSemaphoreConnection();
    if (!$connection_test['reachable']) {
        return array(
            'success' => false,
            'error' => 'Cannot reach Semaphore API. Network issue: ' . $connection_test['error']
        );
    }

    $ch = curl_init();
    $formatted_phone = formatPhoneNumber($phone);

    $message = "Hello $firstname,\n\nWe regret to inform you that your appointment at Quiatson Clinic has been CANCELED.\n\n"
             . "If you would like to book another appointment, please log in to your website account or contact us directly.\n\n"
             . "We apologize for any inconvenience and thank you for your understanding.";

    $parameters = array(
        'apikey' => $api_key,
        'number' => $formatted_phone,
        'message' => $message,
        'sendername' => $sender_name
    );

    curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

    $output = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    error_log("Canceled SMS API Response: " . $output);

    if ($curl_error) return array('success' => false, 'error' => 'CURL Error: ' . $curl_error);
    if ($http_code !== 200) return array('success' => false, 'error' => 'HTTP Error: ' . $http_code . ' - ' . $output);

    $response_data = json_decode($output, true);
    if (json_last_error() !== JSON_ERROR_NONE) return array('success' => false, 'error' => 'Invalid JSON response: ' . $output);

    if (isset($response_data[0]['message_id']) || isset($response_data['message_id'])) {
        return array('success' => true, 'message_id' => $response_data[0]['message_id'] ?? $response_data['message_id'], 'message' => 'Canceled SMS sent successfully');
    }
    return array('success' => false, 'error' => 'SMS failed to send. Response: ' . $output);
}

?>