<?php
// filepath: c:\xampp\htdocs\JEAN\thank_you.php
$status = $_GET['status'] ?? ''; // Retrieve the status from the query parameter
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <!-- Include Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <?php if ($status === 'success'): ?>
        <script>
            // Redirect to index.php after 5 seconds
            setTimeout(() => {
                window.location.href = "../index.php";
            }, 5000);
        </script>
    <?php endif; ?>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-16 text-center">
        <?php if ($status === 'success'): ?>
            <h1 class="text-4xl font-bold text-green-600 mb-4">Thank You!</h1>
            <p class="text-lg text-gray-700">Your feedback has been submitted successfully. We appreciate your response!</p>
            <p class="text-sm text-gray-500 mt-4">You will be redirected to the homepage in 5 seconds...</p>
        <?php else: ?>
            <h1 class="text-4xl font-bold text-red-600 mb-4">Oops!</h1>
            <p class="text-lg text-gray-700">Something went wrong. Please try again later.</p>
        <?php endif; ?>
        <a href="../index.php" class="mt-8 inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700">Go Back to Home</a>
    </div>
</body>
</html>
