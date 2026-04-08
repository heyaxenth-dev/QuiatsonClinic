<?php
/**
 * Handles admin staff registration (POST register) and login (POST login).
 * Forms live in register.php and login.php. Direct GET access redirects to login.
 */
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

include 'database/conn.php';

function admin_register_redirect_success(): void
{
    header('Location: login.php');
    exit;
}

function admin_register_redirect_error(string $message, bool $toRegisterForm = true): void
{
    $_SESSION['status'] = 'Error';
    $_SESSION['status_text'] = $message;
    $_SESSION['status_code'] = 'error';
    $_SESSION['status_btn'] = 'Back';
    header('Location: ' . ($toRegisterForm ? 'register.php' : 'login.php'));
    exit;
}

// -------------------------------------------------------------------------
// Registration
// -------------------------------------------------------------------------
if (isset($_POST['register'])) {
    $username = trim((string) ($_POST['username'] ?? ''));
    $mobile_no = trim((string) ($_POST['mobile_no'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $role = trim((string) ($_POST['role'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $confirm_password = (string) ($_POST['confirm_password'] ?? '');

    $errors = [];

    if ($username === '') {
        $errors[] = 'Username is required.';
    }
    if ($mobile_no === '') {
        $errors[] = 'Mobile number is required.';
    }
    if ($email === '') {
        $errors[] = 'Email is required.';
    }
    if ($role === '') {
        $errors[] = 'Role is required.';
    }
    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    if ($username !== '' && !preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
        $errors[] = 'Username must be 3-20 characters (letters, numbers, underscores only).';
    }

    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($mobile_no !== '' && !preg_match('/^[0-9+\-\s()]{10,15}$/', $mobile_no)) {
        $errors[] = 'Please enter a valid mobile number.';
    }

    if ($role !== '' && !in_array($role, ['Doctor', 'Clinic Assistant'], true)) {
        $errors[] = 'Please select a valid role.';
    }

    if ($password !== '') {
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long.';
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password must contain at least one uppercase letter.';
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password must contain at least one lowercase letter.';
        }
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password must contain at least one number.';
        }
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    if ($username !== '') {
        $stmt = $conn->prepare('SELECT id FROM admin_staff WHERE username = ? LIMIT 1');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = 'This username is already taken.';
        }
        $stmt->close();
    }

    if ($email !== '') {
        $stmt = $conn->prepare('SELECT id FROM admin_staff WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = 'This email is already registered.';
        }
        $stmt->close();
    }

    if ($mobile_no !== '') {
        $stmt = $conn->prepare('SELECT id FROM admin_staff WHERE mobile_no = ? LIMIT 1');
        $stmt->bind_param('s', $mobile_no);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = 'This mobile number is already registered.';
        }
        $stmt->close();
    }

    if ($errors !== []) {
        admin_register_redirect_error(implode(' ', $errors), true);
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    if ($hashed_password === false) {
        admin_register_redirect_error('Could not secure password. Please try again.', true);
    }

    // password_token is NOT NULL in schema; new accounts start with empty token
    $password_token = '';
    $stmt = $conn->prepare(
        'INSERT INTO admin_staff (username, mobile_no, email, role, password, password_token) VALUES (?, ?, ?, ?, ?, ?)'
    );
    if (!$stmt) {
        admin_register_redirect_error('Database error. Please try again later.', true);
    }

    $stmt->bind_param('ssssss', $username, $mobile_no, $email, $role, $hashed_password, $password_token);

    if ($stmt->execute()) {
        $stmt->close();
        $_SESSION['status'] = 'Success';
        $_SESSION['status_text'] = 'Staff account registered successfully! You can sign in now.';
        $_SESSION['status_code'] = 'success';
        $_SESSION['status_btn'] = 'Ok';
        admin_register_redirect_success();
    }

    $err = $stmt->error;
    $stmt->close();
    admin_register_redirect_error('Registration failed: ' . $err, true);
}

// -------------------------------------------------------------------------
// Login
// -------------------------------------------------------------------------
if (isset($_POST['login'])) {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $_SESSION['status'] = 'Error';
        $_SESSION['status_text'] = 'Please enter username and password.';
        $_SESSION['status_code'] = 'error';
        $_SESSION['status_btn'] = 'Back';
        header('Location: login.php');
        exit;
    }

    $stmt = $conn->prepare('SELECT id, username, role, password FROM admin_staff WHERE username = ? LIMIT 1');
    if (!$stmt) {
        $_SESSION['status'] = 'Error';
        $_SESSION['status_text'] = 'Database error. Please try again.';
        $_SESSION['status_code'] = 'error';
        $_SESSION['status_btn'] = 'Back';
        header('Location: login.php');
        exit;
    }

    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $stmt->close();
        $_SESSION['status'] = 'Error';
        $_SESSION['status_text'] = 'Username not found.';
        $_SESSION['status_code'] = 'error';
        $_SESSION['status_btn'] = 'Back';
        header('Location: login.php');
        exit;
    }

    $row = $result->fetch_assoc();
    $stmt->close();

    if (!password_verify($password, $row['password'])) {
        $_SESSION['status'] = 'Error';
        $_SESSION['status_text'] = 'Invalid password.';
        $_SESSION['status_code'] = 'error';
        $_SESSION['status_btn'] = 'Back';
        header('Location: login.php');
        exit;
    }

    $_SESSION['admin_auth'] = true;
    $_SESSION['user_id'] = (int) $row['id'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['role'] = $row['role'];
    $_SESSION['logged'] = 'Welcome back, ' . $row['username'];
    $_SESSION['logged_icon'] = 'success';
    header('Location: admin/home.php');
    exit;
}

header('Location: login.php');
exit;
