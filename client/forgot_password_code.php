<?php
session_start();
include '../database/conn.php';
include '../admin/api/api_key.php';
include 'utils/sms_sender.php';

// Helper: generate numeric OTP
function generate_otp($length = 6) {
	$digits = '0123456789';
	$otp = '';
	for ($i = 0; $i < $length; $i++) {
		$otp .= $digits[random_int(0, strlen($digits) - 1)];
	}
	return $otp;
}

// Action router
$action = $_POST['action'] ?? '';

if ($action === 'request_otp') {
	$mobile = trim($_POST['mobile'] ?? '');

	if ($mobile === '') {
		$_SESSION['status'] = 'Error';
		$_SESSION['status_text'] = 'Mobile number is required.';
		$_SESSION['status_code'] = 'error';
		$_SESSION['status_btn'] = 'Back';
		header('Location: forgot_password.php');
		exit;
	}

	$stmt = $conn->prepare('SELECT id, firstname, mobile_no FROM client WHERE mobile_no = ?');
	$stmt->bind_param('s', $mobile);
	$stmt->execute();
	$result = $stmt->get_result();
	if ($result->num_rows === 0) {
		$_SESSION['status'] = 'Error';
		$_SESSION['status_text'] = 'Mobile number not found.';
		$_SESSION['status_code'] = 'error';
		$_SESSION['status_btn'] = 'Back';
		header('Location: forgot_password.php');
		exit;
	}
	$user = $result->fetch_assoc();
	$stmt->close();

	$otp = generate_otp(6);

	// Store OTP into password_token column if available, else fallback to session
	$upd = $conn->prepare('UPDATE client SET password_token = ? WHERE id = ?');
	$upd->bind_param('si', $otp, $user['id']);
	$upd->execute();
	$affected = $upd->affected_rows;
	$upd->close();

	if ($affected <= 0) {
		// Fallback to session storage keyed by mobile number
		$_SESSION['fp_otp'][$mobile] = [
			'token' => $otp,
			'user_id' => $user['id']
		];
	}

	$sms = sendOTP_SMS($api_key, $sender_name, $user['mobile_no'], $user['firstname'], $otp, 10);
	if ($sms['success']) {
		$_SESSION['status'] = 'Success';
		$_SESSION['status_text'] = 'OTP sent to your registered mobile number.';
		$_SESSION['status_code'] = 'success';
		$_SESSION['status_btn'] = 'Ok';
	} else {
		$_SESSION['status'] = 'Error';
		$_SESSION['status_text'] = 'Failed to send OTP: ' . $sms['error'];
		$_SESSION['status_code'] = 'error';
		$_SESSION['status_btn'] = 'Back';
	}
	header('Location: verify_otp.php?mobile=' . urlencode($mobile));
	exit;
}

if ($action === 'verify_otp') {
	$mobile = trim($_POST['mobile'] ?? '');
	$otp = trim($_POST['otp'] ?? '');

	if ($mobile === '' || $otp === '') {
		$_SESSION['status'] = 'Error';
		$_SESSION['status_text'] = 'Mobile and OTP are required.';
		$_SESSION['status_code'] = 'error';
		$_SESSION['status_btn'] = 'Back';
		header('Location: verify_otp.php?mobile=' . urlencode($mobile));
		exit;
	}

	$stmt = $conn->prepare('SELECT id, password_token FROM client WHERE mobile_no = ?');
	$stmt->bind_param('s', $mobile);
	$stmt->execute();
	$result = $stmt->get_result();
	$dbUser = $result->num_rows ? $result->fetch_assoc() : null;
	$stmt->close();

	$valid = false;
	$userId = null;

	if ($dbUser) {
		$userId = $dbUser['id'];
		if ((string)$dbUser['password_token'] === (string)$otp && $dbUser['password_token'] !== '') {
			$valid = true;
		}
	} else if (isset($_SESSION['fp_otp'][$mobile])) {
		$stored = $_SESSION['fp_otp'][$mobile];
		$userId = $stored['user_id'];
		if ((string)$stored['token'] === (string)$otp) {
			$valid = true;
		}
	}

	if (!$valid || !$userId) {
		$_SESSION['status'] = 'Error';
		$_SESSION['status_text'] = 'Invalid OTP. Please try again.';
		$_SESSION['status_code'] = 'error';
		$_SESSION['status_btn'] = 'Back';
		header('Location: verify_otp.php?mobile=' . urlencode($mobile));
		exit;
	}

	// Mark mobile as verified for password reset
	$_SESSION['reset_mobile'] = $mobile;
	$_SESSION['reset_user_id'] = $userId;
	header('Location: reset_password.php');
	exit;
}

if ($action === 'do_reset') {
	$mobileFromSession = $_SESSION['reset_mobile'] ?? '';
	$userId = $_SESSION['reset_user_id'] ?? null;
	$password = $_POST['password'] ?? '';
	$confirm = $_POST['confirm'] ?? '';

	if ($mobileFromSession === '' || !$userId) {
		$_SESSION['status'] = 'Error';
		$_SESSION['status_text'] = 'Session expired. Please restart the reset process.';
		$_SESSION['status_code'] = 'error';
		$_SESSION['status_btn'] = 'Back';
		header('Location: forgot_password.php');
		exit;
	}

	if ($password === '' || $confirm === '') {
		$_SESSION['status'] = 'Error';
		$_SESSION['status_text'] = 'All fields are required.';
		$_SESSION['status_code'] = 'error';
		$_SESSION['status_btn'] = 'Back';
		header('Location: reset_password.php');
		exit;
	}
	if ($password !== $confirm) {
		$_SESSION['status'] = 'Error';
		$_SESSION['status_text'] = 'Passwords do not match.';
		$_SESSION['status_code'] = 'error';
		$_SESSION['status_btn'] = 'Back';
		header('Location: reset_password.php');
		exit;
	}

	$hashed = password_hash($password, PASSWORD_DEFAULT);
	$upd = $conn->prepare('UPDATE client SET password = ?, password_token = "" WHERE id = ?');
	$upd->bind_param('si', $hashed, $userId);
	$ok = $upd->execute();
	$upd->close();

	unset($_SESSION['fp_otp'][$mobileFromSession]);
	unset($_SESSION['reset_mobile'], $_SESSION['reset_user_id']);

	if ($ok) {
		$_SESSION['status'] = 'Success';
		$_SESSION['status_text'] = 'Password reset successful. Please log in.';
		$_SESSION['status_code'] = 'success';
		$_SESSION['status_btn'] = 'Ok';
		header('Location: ../index.php');
		exit;
	} else {
		$_SESSION['status'] = 'Error';
		$_SESSION['status_text'] = 'Failed to reset password.';
		$_SESSION['status_code'] = 'error';
		$_SESSION['status_btn'] = 'Back';
		header('Location: reset_password.php');
		exit;
	}
}

// Default fallback
header('Location: forgot_password.php');
exit;