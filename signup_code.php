<?php
// admin-register.php
session_start();
include 'database/conn.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["client_register"])) {
    // Sanitize and validate input data
    $firstname = trim($conn->real_escape_string($_POST["firstName"]));
    $lastname = trim($conn->real_escape_string($_POST["lastName"]));
    $email = trim($conn->real_escape_string($_POST["email"]));
    $phone = trim($conn->real_escape_string($_POST["phone"]));
    $dob = $conn->real_escape_string($_POST["dob"]);
    $sex = $conn->real_escape_string($_POST["sex"]);
    $address = trim($conn->real_escape_string($_POST["address"]));
    $password = $_POST["password"];
    $confirm_password = $_POST["confirmPassword"];

    // Server-side validation
    $errors = [];

    // Validate required fields
    if (empty($firstname)) $errors[] = "First name is required.";
    if (empty($lastname)) $errors[] = "Last name is required.";
    if (empty($email)) $errors[] = "Email is required.";
    if (empty($phone)) $errors[] = "Mobile number is required.";
    if (empty($dob)) $errors[] = "Date of birth is required.";
    if (empty($sex)) $errors[] = "Sex is required.";
    if (empty($address)) $errors[] = "Address is required.";
    if (empty($password)) $errors[] = "Password is required.";

    // Validate email format
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    // Validate phone format
    if (!empty($phone) && !preg_match('/^[0-9+\-\s()]{10,15}$/', $phone)) {
        $errors[] = "Please enter a valid phone number.";
    }


    // Validate password strength and confirmation
    if (!empty($password)) {
        if (strlen($password) < 8 ||
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[a-z]/', $password) ||
            !preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must be at least 8 characters long and contain uppercase, lowercase, and a number.";
        }
        if ($password !== $confirm_password) {
            $errors[] = "Passwords do not match.";
        }
    }


    // Check for duplicate email and phone (single query for efficiency)
    if (!empty($email) || !empty($phone)) {
        $stmt = $conn->prepare("SELECT id, email, mobile_no FROM client WHERE email = ? OR mobile_no = ? LIMIT 1");
        $stmt->bind_param("ss", $email, $phone);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            if ($row['email'] === $email) {
                $errors[] = "This email is already registered.";
            }
            if ($row['mobile_no'] === $phone) {
                $errors[] = "This mobile number is already registered.";
            }
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
            header("Location: index.php#appointment");
            exit;
        }
    } else {
        $_SESSION['status'] = "Error";
        $_SESSION['status_text'] = "firstname not found.";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_btn'] = "Back";
        header("Location: index.php#appointment");
        exit;
    }
    $stmt->close();
}

$conn->close();
?>