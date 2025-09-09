<?php
include '../database/conn.php';
session_start();

// Check if user is authenticated as admin
if (!isset($_SESSION['admin_auth']) || $_SESSION['admin_auth'] !== true) {
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit;
}

$response = ["success" => false, "message" => "Unknown error."];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        // Validate and sanitize input
        $new_username = trim($_POST['username'] ?? '');
        $new_email = trim($_POST['email'] ?? '');
        $new_mobile_no = trim($_POST['mobile_no'] ?? '');
        $user_id = $_SESSION['user_id'];

        // Basic validation
        if (empty($new_username)) {
            $response["message"] = "Username is required.";
        } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            $response["message"] = "Please enter a valid email address.";
        } elseif (!empty($new_mobile_no) && !preg_match('/^[0-9+\-\s()]+$/', $new_mobile_no)) {
            $response["message"] = "Please enter a valid mobile number.";
        } else {
            // Check if username is already taken by another admin
            $stmt = $conn->prepare("SELECT id FROM admin_staff WHERE username = ? AND id != ?");
            $stmt->bind_param("si", $new_username, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if ($result->num_rows > 0) {
                $response["message"] = "Username is already taken by another admin.";
            } else {
                // Check if email is already taken by another admin
                $stmt = $conn->prepare("SELECT id FROM admin_staff WHERE email = ? AND id != ?");
                $stmt->bind_param("si", $new_email, $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();

                if ($result->num_rows > 0) {
                    $response["message"] = "Email address is already taken by another admin.";
                } else {
                    // Update profile
                    $stmt = $conn->prepare("UPDATE admin_staff SET username=?, email=?, mobile_no=? WHERE id=?");
                    $stmt->bind_param("sssi", $new_username, $new_email, $new_mobile_no, $user_id);
                    
                    if ($stmt->execute()) {
                        // Update session variables
                        $_SESSION['username'] = $new_username;
                        $response = ["success" => true, "message" => "Profile updated successfully."];
                    } else {
                        $response["message"] = "Failed to update profile. Please try again.";
                    }
                    $stmt->close();
                }
            }
        }
    }
    
    if (isset($_POST['change_password'])) {
        $user_id = $_SESSION['user_id'];
        $currentPassword = $_POST['password'] ?? '';
        $newPassword = $_POST['newpassword'] ?? '';
        $renewPassword = $_POST['renewpassword'] ?? '';

        // Validation
        if (empty($currentPassword) || empty($newPassword) || empty($renewPassword)) {
            $response["message"] = "All password fields are required.";
        } elseif (strlen($newPassword) < 6) {
            $response["message"] = "New password must be at least 6 characters long.";
        } elseif ($newPassword !== $renewPassword) {
            $response["message"] = "New passwords do not match.";
        } elseif ($currentPassword === $newPassword) {
            $response["message"] = "New password must be different from current password.";
        } else {
            // Verify current password
            $stmt = $conn->prepare("SELECT password FROM admin_staff WHERE id=?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($hashedPassword);
            $stmt->fetch();
            $stmt->close();

            if (password_verify($currentPassword, $hashedPassword)) {
                // Update password
                $newHashed = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE admin_staff SET password=? WHERE id=?");
                $stmt->bind_param("si", $newHashed, $user_id);
                
                if ($stmt->execute()) {
                    $response = ["success" => true, "message" => "Password changed successfully."];
                } else {
                    $response["message"] = "Failed to change password. Please try again.";
                }
                $stmt->close();
            } else {
                $response["message"] = "Current password is incorrect.";
            }
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);