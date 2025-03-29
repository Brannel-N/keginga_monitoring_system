<?php
session_start();
include 'include/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the token is valid and not expired
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expires > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Update the password and clear the reset token
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
        $stmt->execute([$password, $user['id']]);

        $_SESSION['message'] = "Your password has been reset successfully.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['message'] = "Invalid or expired token.";
    }
}

$token = $_GET['token'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Keginga Tea Farmers</title>
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <div class="reset-password-container">
        <h2>Reset Password</h2>
        <?php if (isset($_SESSION['message'])): ?>
            <p class="message"><?php echo $_SESSION['message']; ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        <form method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" class="btn">Reset Password</button>
        </form>
    </div>
</body>
</html>