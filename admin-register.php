<?php
// admin-register.php
session_start();
include 'database/conn.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    // Sanitize and validate input data
    $username = trim($conn->real_escape_string($_POST["username"]));
    $mobile_no = trim($conn->real_escape_string($_POST["mobile_no"]));
    $email = trim($conn->real_escape_string($_POST["email"]));
    $role = $conn->real_escape_string($_POST["role"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Server-side validation
    $errors = [];

    // Validate required fields
    if (empty($username)) $errors[] = "Username is required.";
    if (empty($mobile_no)) $errors[] = "Mobile number is required.";
    if (empty($email)) $errors[] = "Email is required.";
    if (empty($role)) $errors[] = "Role is required.";
    if (empty($password)) $errors[] = "Password is required.";

    // Validate username format
    if (!empty($username) && !preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
        $errors[] = "Username must be 3-20 characters, letters, numbers, and underscores only.";
    }

    // Validate email format
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    // Validate mobile format
    if (!empty($mobile_no) && !preg_match('/^[0-9+\-\s()]{10,15}$/', $mobile_no)) {
        $errors[] = "Please enter a valid mobile number.";
    }

    // Validate role
    if (!empty($role) && !in_array($role, ['Doctor', 'Clinic Assistant'])) {
        $errors[] = "Please select a valid role.";
    }

    // Validate password strength
    if (!empty($password)) {
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long.";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter.";
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter.";
        }
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one number.";
        }
    }

    // Validate password confirmation
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Check for duplicate username
    if (!empty($username)) {
        $stmt = $conn->prepare("SELECT id FROM admin_staff WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "This username is already taken.";
        }
        $stmt->close();
    }

    // Check for duplicate email
    if (!empty($email)) {
        $stmt = $conn->prepare("SELECT id FROM admin_staff WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "This email is already registered.";
        }
        $stmt->close();
    }

    // Check for duplicate mobile number
    if (!empty($mobile_no)) {
        $stmt = $conn->prepare("SELECT id FROM admin_staff WHERE mobile_no = ? LIMIT 1");
        $stmt->bind_param("s", $mobile_no);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "This mobile number is already registered.";
        }
        $stmt->close();
    }

    // If there are validation errors, redirect back with error message
    if (!empty($errors)) {
        $_SESSION['status'] = "Error";
        $_SESSION['status_text'] = implode(" ", $errors);
        $_SESSION['status_code'] = "error";
        $_SESSION['status_btn'] = "Back";
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO admin_staff (username, mobile_no, email, role, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $mobile_no, $email, $role, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['status'] = "Success";
        $_SESSION['status_text'] = "Staff account registered successfully!";
        $_SESSION['status_code'] = "success";
        $_SESSION['status_btn'] = "Ok";
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    } else {
        $_SESSION['status'] = "Error";
        $_SESSION['status_text'] = "Error: " . $stmt->error;
        $_SESSION['status_code'] = "error";
        $_SESSION['status_btn'] = "Back";
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $conn->real_escape_string($_POST["username"]);
    $password = $conn->real_escape_string($_POST["password"]);

    $stmt = $conn->prepare("SELECT * FROM admin_staff WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_auth'] = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['logged'] = "Welcome back, " . $row['username'];
            $_SESSION['logged_icon'] = "success";
            header("Location: admin/home.php"); // Redirect to the dashboard or home page
            exit;
        } else {
            $_SESSION['status'] = "Error";
            $_SESSION['status_text'] = "Invalid password.";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_btn'] = "Back";
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit;
        }
    } else {
        $_SESSION['status'] = "Error";
        $_SESSION['status_text'] = "Username not found.";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_btn'] = "Back";
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    }
    $stmt->close();
}

$conn->close();
?>