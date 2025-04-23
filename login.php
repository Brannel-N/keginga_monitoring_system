<?php
session_start();

$email = $_SESSION['login_email'] ?? '';
$step = $_SESSION['login_step'] ?? 1;
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

// Reset all steps
if (isset($_GET['reset'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-600 ">
    <section class="flex items-center justify-center h-screen">
         <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-green-600">Welcome To Keginga Farmers App</h2>
        <p class="text-gray-600 text-sm py-2 text-center mb-6">Please login to continue.</p>

        <?php if (!empty($error)): ?>            <p class="text-red-600 text-center mb-4"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <?php if ($step === 1): ?>
                <form method="POST" action="./processes/login_process.php" class="space-y-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Email</label>
                        <input type="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:ring-green-600 focus:ring-2">
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">Next</button>
                </form>

        <?php elseif ($step === 2): ?>
                <form method="POST" action="./processes/login_process.php" class="space-y-4">
                    <p class="text-sm text-gray-700">Email: <strong><?php echo htmlspecialchars($email); ?></strong></p>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Password</label>
                        <input type="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:ring-green-600 focus:ring-2">
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">Login</button>
                </form>

        <?php elseif ($step === 3): ?>
                <form method="POST" action="./processes/login_process.php" class="space-y-4">
                    <p class="text-sm text-gray-700">Email: <strong><?php echo htmlspecialchars($email); ?></strong></p>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Phone Number</label>
                        <input type="text" name="phone" required class="w-full px-4 py-2 border rounded-lg focus:ring-green-600 focus:ring-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">New Password</label>
                        <input type="password" name="new_password" required class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Confirm Password</label>
                        <input type="password" name="confirm_password" required class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">Create Password & Login</button>
                </form>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="?reset=true" class="text-sm text-green-600 hover:underline">Start Over</a>
        </div>
    </div>
    </section>
</body>
</html>
