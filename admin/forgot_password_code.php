<?php
session_start();
include '../database/conn.php';
include 'api/api_key.php';
include '../client/utils/sms_sender.php';

function generate_otp($length = 6) {
	$digits = '0123456789';
	$otp = '';
	for ($i = 0; $i < $length; $i++) {
		$otp .= $digits[random_int(0, strlen($digits) - 1)];
	}
	return $otp;
}

$action = $_POST['action'] ?? '';

// Utility: check if a column exists on admin_staff
function admin_column_exists($conn, $column) {
	$res = $conn->query("SHOW COLUMNS FROM admin_staff LIKE '" . $conn->real_escape_string($column) . "'");
	return $res && $res->num_rows > 0;
}

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

	if (!admin_column_exists($conn, 'mobile_no')) {
		$_SESSION['status'] = 'Error';
		$_SESSION['status_text'] = 'Admin accounts do not have mobile numbers configured. Please add a mobile_no column and values for admin accounts.';
		$_SESSION['status_code'] = 'error';
		$_SESSION['status_btn'] = 'Back';
		header('Location: forgot_password.php');
		exit;
	}

	$stmt = $conn->prepare('SELECT id, username, mobile_no FROM admin_staff WHERE mobile_no = ?');
	$stmt->bind_param('s', $mobile);
	$stmt->execute();
	$result = $stmt->get_result();
	if ($result->num_rows === 0) {
		$_SESSION['status'] = 'Error';
		$_SESSION['status_text'] = 'Mobile number not found for any admin account.';
		$_SESSION['status_code'] = 'error';
		$_SESSION['status_btn'] = 'Back';
		header('Location: forgot_password.php');
		exit;
	}
	$user = $result->fetch_assoc();
	$stmt->close();

	$otp = generate_otp(6);

	$stored_in_db = false;
	if (admin_column_exists($conn, 'password_token')) {
		$upd = $conn->prepare('UPDATE admin_staff SET password_token = ? WHERE id = ?');
		$upd->bind_param('si', $otp, $user['id']);
		$upd->execute();
		$stored_in_db = $upd->affected_rows > 0;
		$upd->close();
	}
	if (!$stored_in_db) {
		$_SESSION['admin_fp_otp'][$mobile] = [
			'token' => $otp,
			'user_id' => $user['id']
		];
	}

	$sms = sendOTP_SMS($api_key, $sender_name, $user['mobile_no'], $user['username'], $otp, 10);
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

	$userId = null;
	$valid = false;

	if (admin_column_exists($conn, 'mobile_no') && admin_column_exists($conn, 'password_token')) {
		$stmt = $conn->prepare('SELECT id, password_token FROM admin_staff WHERE mobile_no = ?');
		$stmt->bind_param('s', $mobile);
		$stmt->execute();
		$result = $stmt->get_result();
		$dbUser = $result->num_rows ? $result->fetch_assoc() : null;
		$stmt->close();
		if ($dbUser && (string)$dbUser['password_token'] !== '' && (string)$dbUser['password_token'] === (string)$otp) {
			$userId = $dbUser['id'];
			$valid = true;
		}
	}
	if (!$valid && isset($_SESSION['admin_fp_otp'][$mobile])) {
		$stored = $_SESSION['admin_fp_otp'][$mobile];
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

	$_SESSION['admin_reset_mobile'] = $mobile;
	$_SESSION['admin_reset_user_id'] = $userId;
	header('Location: reset_password.php');
	exit;
}

if ($action === 'do_reset') {
	$mobileFromSession = $_SESSION['admin_reset_mobile'] ?? '';
	$userId = $_SESSION['admin_reset_user_id'] ?? null;
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
	if (admin_column_exists($conn, 'password_token')) {
		$upd = $conn->prepare('UPDATE admin_staff SET password = ?, password_token = "" WHERE id = ?');
		$upd->bind_param('si', $hashed, $userId);
		$ok = $upd->execute();
		$upd->close();
	} else {
		$upd = $conn->prepare('UPDATE admin_staff SET password = ? WHERE id = ?');
		$upd->bind_param('si', $hashed, $userId);
		$ok = $upd->execute();
		$upd->close();
	}

	unset($_SESSION['admin_fp_otp'][$mobileFromSession]);
	unset($_SESSION['admin_reset_mobile'], $_SESSION['admin_reset_user_id']);

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

header('Location: forgot_password.php');
exit;
