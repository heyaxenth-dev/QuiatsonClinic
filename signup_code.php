<?php
// admin-register.php
session_start();
include 'database/conn.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["client_register"])) {
    $firstname = $conn->real_escape_string($_POST["firstName"]);
    $lastname = $conn->real_escape_string($_POST["lastName"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $phone = $conn->real_escape_string($_POST["phone"]);
    $dob = $conn->real_escape_string($_POST["dob"]);
    $sex = $conn->real_escape_string($_POST["sex"]);
    $address = $conn->real_escape_string($_POST["address"]);
    $password = $conn->real_escape_string($_POST["password"]);
    $confirm_password = $conn->real_escape_string($_POST["confirmPassword"]);

    if ($password !== $confirm_password) {
        $_SESSION['status'] = "Error";
        $_SESSION['status_text'] = "Passwords do not match.";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_btn'] = "Back";
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO client (firstname, lastname, mobile_no, email, dob, sex, password, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $firstname, $lastname, $phone, $email, $dob, $sex, $hashed_password, $address);

    if ($stmt->execute()) {
        $_SESSION['status'] = "Success";
        $_SESSION['status_text'] = "Account registered successfully!";
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_client'])) {
    $email = $conn->real_escape_string($_POST["email"]);
    $password = $conn->real_escape_string($_POST["password"]);

    $stmt = $conn->prepare("SELECT * FROM client WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['client_auth'] = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['firstname'] = $row['firstname'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['phone'] = $row['phone'];
            $_SESSION['logged'] = "Welcome back, " . $row['firstname'] . "!";
            $_SESSION['logged_icon'] = "success";
            header("Location: client/homepage.php"); // Redirect to the dashboard or home page
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
        $_SESSION['status_text'] = "firstname not found.";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_btn'] = "Back";
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    }
    $stmt->close();
}

$conn->close();
?>