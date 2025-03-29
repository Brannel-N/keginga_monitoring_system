<?php
session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check if the email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Generate a unique token
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Save the token in the database
        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?");
        $stmt->execute([$token, $expires, $user['id']]);

        // Send password reset email
        $to = $email;
        $subject = "Password Reset Request";
        $reset_link = "http://yourdomain.com/reset_password_form.php?token=$token";
        $message = "Dear User,\n\nTo reset your password, please click the link below:\n\n$reset_link\n\nThis link will expire in 1 hour.\n\nBest regards,\nKeginga Tea Farmers Team";
        $headers = "From: no-reply@kegingatea.com";

        if (mail($to, $subject, $message, $headers)) {
            $_SESSION['message'] = "A password reset link has been sent to your email.";
        } else {
            $_SESSION['message'] = "Error sending the password reset email.";
        }
    } else {
        $_SESSION['message'] = "No account found with that email address.";
    }
}
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
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <button type="submit" class="btn">Reset Password</button>
        </form>
    </div>
</body>
</html>