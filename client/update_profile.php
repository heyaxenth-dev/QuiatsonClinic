<?php
include '../database/conn.php';
session_start();
$response = ["success" => false, "message" => "Unknown error."];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $new_firstname = trim($_POST['firstname']);
        $new_lastname = trim($_POST['lastname']);
        $new_email = trim($_POST['email']);
        $new_mobile_no = trim($_POST['mobile_no']);
        $new_address = trim($_POST['address']);
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("UPDATE users SET firstname=?, lastname=?, email=?, mobile_no=?, address=? WHERE id=?");
        $stmt->bind_param("sssssi", $new_firstname, $new_lastname, $new_email, $new_mobile_no, $new_address, $user_id);
        if ($stmt->execute()) {
            $response = ["success" => true, "message" => "Profile updated successfully."];
        } else {
            $response["message"] = "Failed to update profile.";
        }
        $stmt->close();
    }
    if (isset($_POST['change_password'])) {
        $user_id = $_SESSION['user_id'];
        $currentPassword = $_POST['password'];
        $newPassword = $_POST['newpassword'];
        $renewPassword = $_POST['renewpassword'];
        if ($newPassword !== $renewPassword) {
            $response["message"] = "New passwords do not match.";
        } else {
            $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($hashedPassword);
            $stmt->fetch();
            $stmt->close();
            if (password_verify($currentPassword, $hashedPassword)) {
                $newHashed = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
                $stmt->bind_param("si", $newHashed, $user_id);
                if ($stmt->execute()) {
                    $response = ["success" => true, "message" => "Password changed successfully."];
                } else {
                    $response["message"] = "Failed to change password.";
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
