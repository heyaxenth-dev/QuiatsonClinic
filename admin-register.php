<?php
// admin-register.php
session_start();
include 'database/conn.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    $username = $conn->real_escape_string($_POST["username"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $role = $conn->real_escape_string($_POST["role"]);
    $password = $conn->real_escape_string($_POST["password"]);
    $confirm_password = $conn->real_escape_string($_POST["confirm_password"]);

    if ($password !== $confirm_password) {
        $_SESSION['status'] = "Error";
        $_SESSION['status_text'] = "Passwords do not match.";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_btn'] = "Back";
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO admin_staff (username, email, role, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $role, $hashed_password);

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