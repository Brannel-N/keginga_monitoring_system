<?php
require_once '../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $newPassword = $_POST['new_password'] ?? null;
    $confirmPassword = $_POST['confirm_password'] ?? null;

    // Step 1: Email submission
    if ($email && !$password && !$newPassword) {
        $email = mysqli_real_escape_string($conn, $email);

        $query = "SELECT * FROM users WHERE email = ? AND isActive = 1 LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows !== 1) {
            $_SESSION['error'] = "Account not found.";
            header("Location: ../login.php");
            exit();
        }

        $user = $result->fetch_assoc();
        $_SESSION['login_email'] = $email;

        if ((int) $user['isAdmin'] === 1 || !empty($user['password'])) {
            $_SESSION['login_step'] = 2;
        } else {
            $_SESSION['login_step'] = 3;
        }

        header("Location: ../login.php");
        exit();
    }

    // Step 2: Password login
    if (!empty($_SESSION['login_email']) && $password) {
        $email = $_SESSION['login_email'];
        $password = mysqli_real_escape_string($conn, $password);

        $query = "SELECT * FROM users WHERE email = ? AND isActive = 1 LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows !== 1) {
            $_SESSION['error'] = "Account not found.";
            session_destroy();
            header("Location: ../login.php");
            exit();
        }

        $user = $result->fetch_assoc();

        if (!password_verify($password, $user['password'])) {
            $_SESSION['error'] = "Invalid password.";
            $_SESSION['login_step'] = 2;
            header("Location: ../login.php");
            exit();
        }

        $_SESSION['userId'] = $user['userId'];
        $_SESSION['fullName'] = $user['fullName'];
        // $_SESSION['isAdmin'] = (int) $user['isAdmin'] === 1;
        $_SESSION['isAdmin'] = $user['isAdmin'];
        $_SESSION['isFarmer'] = !$user['isAdmin'];
        

        // session_unset(); // clear temporary login steps
        session_write_close();

        header("Location: " . ($_SESSION['isAdmin'] ? "../admin/index.php" : "../farmer/index.php"));
        exit();
    }

    // Step 3: Phone verification & password setup
    if (!empty($_SESSION['login_email']) && $phone && $newPassword && $confirmPassword) {
        $email = $_SESSION['login_email'];

        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = "Passwords do not match.";
            $_SESSION['login_step'] = 3;
            header("Location: ../login.php");
            exit();
        }

        $phone = mysqli_real_escape_string($conn, $phone);
        $newPassword = mysqli_real_escape_string($conn, $newPassword);

        $verifyQuery = "SELECT * FROM users WHERE email = ? AND phoneNumber = ? LIMIT 1";
        $verifyStmt = $conn->prepare($verifyQuery);
        $verifyStmt->bind_param("ss", $email, $phone);
        $verifyStmt->execute();
        $verifyResult = $verifyStmt->get_result();

        if ($verifyResult->num_rows !== 1) {
            $_SESSION['error'] = "Phone number does not match our records.";
            $_SESSION['login_step'] = 3;
            header("Location: ../login.php");
            exit();
        }

        $user = $verifyResult->fetch_assoc();
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateQuery = "UPDATE users SET password = ? WHERE email = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ss", $hashedPassword, $email);
        $updateStmt->execute();

        $_SESSION['userId'] = $user['userId'];
        $_SESSION['fullName'] = $user['fullName'];
        $_SESSION['isAdmin'] = false;
        $_SESSION['isFarmer'] = true;

        session_unset(); // clear temporary login steps
        session_write_close();

        header("Location: ../farmer/index.php");
        exit();
    }

    // Invalid access
    $_SESSION['error'] = "Invalid request. Please try again.";
    header("Location: ../login.php");
    exit();
} else {
    header("Location: ../login.php");
    exit();
}
