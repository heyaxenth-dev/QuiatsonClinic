<?php

/**
 * Semaphore SMS helpers (Quiatson Clinic).
 *
 * Optional overrides (define before including this file, e.g. in api_key.php):
 *   define('QUIATSON_SMS_INSECURE_SSL', true);  // XAMPP without CA bundle only — not for production
 *   define('QUIATSON_SMS_DEBUG_LOG', true);     // log API responses to error_log
 */

if (!defined('SEMAPHORE_API_BASE')) {
    define('SEMAPHORE_API_BASE', 'https://semaphore.co/api/v4');
}

/**
 * @return bool
 */
function quiatson_sms_insecure_ssl(): bool
{
    return defined('QUIATSON_SMS_INSECURE_SSL') && constant('QUIATSON_SMS_INSECURE_SSL') === true;
}

/**
 * @return bool
 */
function quiatson_sms_debug_log(): bool
{
    return defined('QUIATSON_SMS_DEBUG_LOG') && constant('QUIATSON_SMS_DEBUG_LOG') === true;
}

/**
 * Apply shared cURL options for Semaphore HTTPS calls.
 *
 * @param CurlHandle|resource $ch
 */
function semaphore_apply_curl_ssl($ch): void
{
    if (quiatson_sms_insecure_ssl()) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    } else {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    }
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'QuiatsonClinic/1.0 PHP-cURL');
}

/**
 * Lightweight reachability + optional API key check.
 *
 * @return array{reachable: bool, http_code: int, error: string}
 */
function testSemaphoreConnection(?string $api_key = null): array
{
    $ch = curl_init();
    $url = SEMAPHORE_API_BASE . '/account';
    if ($api_key !== null && $api_key !== '') {
        $url .= '?apikey=' . rawurlencode($api_key);
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    semaphore_apply_curl_ssl($ch);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

    $output = curl_exec($ch);
    $http_code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch) ?: '';
    curl_close($ch);

    $reachable = $curl_error === '' && $http_code > 0;

    if (quiatson_sms_debug_log()) {
        error_log('Semaphore account check HTTP ' . $http_code . ' err=' . $curl_error);
    }

    return [
        'reachable' => $reachable,
        'http_code' => $http_code,
        'error' => $curl_error,
        'response' => is_string($output) ? $output : '',
    ];
}

/**
 * Normalize PH mobile for Semaphore (E.164 without +).
 *
 * @return string|null null if invalid
 */
function formatPhoneNumber(string $phone): ?string
{
    $digits = preg_replace('/\D/', '', $phone);
    if ($digits === null || $digits === '') {
        return null;
    }
    if ($digits[0] === '0') {
        $digits = '63' . substr($digits, 1);
    } elseif (strpos($digits, '63') !== 0) {
        $digits = '63' . $digits;
    }
    $len = strlen($digits);
    if ($len < 12 || $len > 15) {
        return null;
    }
    return $digits;
}

/**
 * POST /messages and interpret Semaphore JSON (single object or array of results).
 *
 * @return array{success: bool, error?: string, message_id?: mixed, message?: string}
 */
function semaphore_post_message(string $api_key, string $formatted_phone, string $message, string $sender_name): array
{
    if ($api_key === '') {
        return ['success' => false, 'error' => 'SMS API key is not configured.'];
    }

    $parameters = [
        'apikey' => $api_key,
        'number' => $formatted_phone,
        'message' => $message,
        'sendername' => $sender_name,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, SEMAPHORE_API_BASE . '/messages');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    semaphore_apply_curl_ssl($ch);

    $output = curl_exec($ch);
    $http_code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch) ?: '';
    curl_close($ch);

    if (quiatson_sms_debug_log()) {
        error_log('Semaphore messages HTTP ' . $http_code . ' curl_err=' . $curl_error);
    }

    if ($curl_error !== '') {
        return ['success' => false, 'error' => 'CURL error: ' . $curl_error];
    }

    if ($http_code !== 200) {
        $snippet = is_string($output) ? substr($output, 0, 500) : '';
        return ['success' => false, 'error' => 'HTTP ' . $http_code . ($snippet !== '' ? ': ' . $snippet : '')];
    }

    if (!is_string($output)) {
        return ['success' => false, 'error' => 'Empty response from SMS API.'];
    }

    $response_data = json_decode($output, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['success' => false, 'error' => 'Invalid JSON from SMS API.'];
    }

    $message_id = semaphore_extract_message_id($response_data);
    if ($message_id !== null) {
        return [
            'success' => true,
            'message_id' => $message_id,
            'message' => 'SMS sent successfully',
        ];
    }

    $api_msg = '';
    if (is_array($response_data)) {
        if (isset($response_data['message']) && is_string($response_data['message'])) {
            $api_msg = $response_data['message'];
        } elseif (isset($response_data[0]['message']) && is_string($response_data[0]['message'])) {
            $api_msg = $response_data[0]['message'];
        }
    }

    return [
        'success' => false,
        'error' => 'SMS was not accepted by the API.' . ($api_msg !== '' ? ' ' . $api_msg : ''),
    ];
}

/**
 * @param mixed $response_data
 * @return mixed|null
 */
function semaphore_extract_message_id($response_data)
{
    if (!is_array($response_data)) {
        return null;
    }
    if (isset($response_data['message_id'])) {
        return $response_data['message_id'];
    }
    if (isset($response_data[0]) && is_array($response_data[0]) && isset($response_data[0]['message_id'])) {
        return $response_data[0]['message_id'];
    }
    return null;
}

/**
 * @return array{success: bool, error?: string, message_id?: mixed, message?: string}
 */
function sendSMS($api_key, $sender_name, $phone, $firstname, $sendDate, $time_slot)
{
    $conn = testSemaphoreConnection($api_key);
    if (!$conn['reachable']) {
        return [
            'success' => false,
            'error' => 'Cannot reach Semaphore API. ' . ($conn['error'] !== '' ? $conn['error'] : 'Network error'),
        ];
    }

    $formatted = formatPhoneNumber((string) $phone);
    if ($formatted === null) {
        return ['success' => false, 'error' => 'Invalid mobile number for SMS.'];
    }

    $firstname = trim((string) $firstname);
    $sendDate = trim((string) $sendDate);
    $time_slot = trim((string) $time_slot);

    $message = "Hi {$firstname},\n\n"
        . "Your appointment at Quiatson Clinic is CONFIRMED.\n\n"
        . "Date: {$sendDate}\n"
        . "Time: {$time_slot}\n"
        . "Location: Quiatson Clinic\n\n"
        . "Please:\n"
        . "- Arrive at least 15 minutes before your schedule.\n"
        . "- Bring a valid ID and any medical records or lab results (if available).\n\n"
        . "You can reschedule or cancel anytime by signing in to your Quiatson Clinic account (My Appointments).\n\n"
        . "Thank you and see you soon!";

    return semaphore_post_message(
        (string) $api_key,
        $formatted,
        $message,
        (string) $sender_name
    );
}

/**
 * @return array{success: bool, error?: string, message_id?: mixed, message?: string}
 */
function sendRescheduledSMS($api_key, $sender_name, $phone, $firstname, $newDate, $newTime)
{
    $conn = testSemaphoreConnection($api_key);
    if (!$conn['reachable']) {
        return [
            'success' => false,
            'error' => 'Cannot reach Semaphore API. ' . ($conn['error'] !== '' ? $conn['error'] : 'Network error'),
        ];
    }

    $formatted = formatPhoneNumber((string) $phone);
    if ($formatted === null) {
        return ['success' => false, 'error' => 'Invalid mobile number for SMS.'];
    }

    $firstname = trim((string) $firstname);
    $message = "Hi {$firstname},\n\n"
        . "Your appointment at Quiatson Clinic has been RESCHEDULED.\n\n"
        . "New date: {$newDate}\n"
        . "New time: {$newTime}\n"
        . "Location: Quiatson Clinic\n\n"
        . "Please arrive at least 15 minutes early and bring any important documents or lab results.\n\n"
        . "You can manage appointments from your Quiatson Clinic account.\n\n"
        . "Thank you for your patience.";

    $result = semaphore_post_message((string) $api_key, $formatted, $message, (string) $sender_name);
    if ($result['success'] && isset($result['message'])) {
        $result['message'] = 'Rescheduled SMS sent successfully';
    }
    return $result;
}

/**
 * @return array{success: bool, error?: string, message_id?: mixed, message?: string}
 */
function sendCanceledSMS($api_key, $sender_name, $phone, $firstname)
{
    $conn = testSemaphoreConnection($api_key);
    if (!$conn['reachable']) {
        return [
            'success' => false,
            'error' => 'Cannot reach Semaphore API. ' . ($conn['error'] !== '' ? $conn['error'] : 'Network error'),
        ];
    }

    $formatted = formatPhoneNumber((string) $phone);
    if ($formatted === null) {
        return ['success' => false, 'error' => 'Invalid mobile number for SMS.'];
    }

    $firstname = trim((string) $firstname);
    $message = "Hi {$firstname},\n\n"
        . "Your appointment at Quiatson Clinic has been CANCELLED.\n\n"
        . "If this was not intentional, you may book a new appointment anytime through your Quiatson Clinic account.\n\n"
        . "Thank you for your understanding.";

    $result = semaphore_post_message((string) $api_key, $formatted, $message, (string) $sender_name);
    if ($result['success'] && isset($result['message'])) {
        $result['message'] = 'Canceled SMS sent successfully';
    }
    return $result;
}

/**
 * Send OTP for password reset via Semaphore.
 *
 * @return array{success: bool, error?: string, message_id?: mixed, message?: string}
 */
function sendOTP_SMS($api_key, $sender_name, $phone, $firstname, $otp_code, $valid_minutes = 10)
{
    $conn = testSemaphoreConnection($api_key);
    if (!$conn['reachable']) {
        return [
            'success' => false,
            'error' => 'Cannot reach Semaphore API. ' . ($conn['error'] !== '' ? $conn['error'] : 'Network error'),
        ];
    }

    $formatted = formatPhoneNumber((string) $phone);
    if ($formatted === null) {
        return ['success' => false, 'error' => 'Invalid mobile number for SMS.'];
    }

    $firstname = trim((string) $firstname);
    $otp_code = trim((string) $otp_code);
    $valid_minutes = max(1, (int) $valid_minutes);

    $message = "Hi {$firstname},\n\n"
        . "Your Quiatson Clinic password reset code is: {$otp_code}\n"
        . "This code expires in {$valid_minutes} minutes.\n\n"
        . "If you did not request this, ignore this message.\n\n"
        . "- Quiatson Clinic";

    $result = semaphore_post_message((string) $api_key, $formatted, $message, (string) $sender_name);
    if ($result['success'] && isset($result['message'])) {
        $result['message'] = 'OTP SMS sent successfully';
    }
    return $result;
}
