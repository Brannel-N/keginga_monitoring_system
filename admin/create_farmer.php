<?php
// Start session to access user data
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../login.php");
    exit();
}
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
if (isset($_SESSION['error'])) {
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Create Farmer</title>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- Tailwind CSS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>
        <!-- Main Content -->
        <div id="main-content" class="flex-1 flex flex-col">
            <!-- Topbar -->
            <div class="bg-white shadow flex justify-between items-center">
                <?php include 'includes/topbar.php'; ?>
            </div>

            <!-- Main Section -->
            <div class="flex-1 p-6">
                <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-semibold mb-4">Create Farmer</h2>
                    <?php if ($error): ?>
                        <div class="bg-red-100 text-red-700 p-4 mb-4 rounded-lg" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    <div id="feedbackMessage" class="hidden p-4 mb-4 text-sm rounded-lg" role="alert"></div>
                    <script>
                        document.getElementById('createFarmerForm').addEventListener('submit', async function (event) {
                            event.preventDefault(); // Prevent default form submission

                            const form = event.target;
                            const formData = new FormData(form);

                            try {
                                const response = await fetch(form.action, {
                                    method: form.method,
                                    body: formData
                                });

                                const result = await response.json();

                                const feedbackMessage = document.getElementById('feedbackMessage');
                                if (result.status === 'success') {
                                    feedbackMessage.textContent = result.message;
                                    feedbackMessage.className = 'block p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg';
                                } else {
                                    feedbackMessage.textContent = result.message || 'An error occurred.';
                                    feedbackMessage.className = 'block p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg';
                                }
                            } catch (error) {
                                const feedbackMessage = document.getElementById('feedbackMessage');
                                feedbackMessage.textContent = 'An unexpected error occurred.';
                                feedbackMessage.className = 'block p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg';
                            }
                        });
                    </script>
                    <form action="processes/create_farmer.php" method="POST" id="createFarmerForm">
                        <div class="mb-4">
                            <label for="fullName" class="block text-gray-700 font-medium mb-2">Full Name</label>
                            <input type="text" id="fullName" name="fullName" class="w-full border border-gray-300 p-2 rounded-lg focus:ring focus:ring-blue-200 focus:outline-green-500" required>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                            <input type="email" id="email" name="email" class="w-full border border-gray-300 p-2 rounded-lg focus:ring focus:ring-blue-200 focus:outline-green-500" required>
                        </div>
                        <div class="mb-4">
                            <label for="phoneNumber" class="block text-gray-700 font-medium mb-2">Phone Number</label>
                            <input type="text" id="phoneNumber" name="phoneNumber" class="w-full border border-gray-300 p-2 rounded-lg focus:ring focus:ring-blue-200 focus:outline-green-500" required>
                        </div>
                        <div class="mb-4">
                            <label for="location" class="block text-gray-700 font-medium mb-2">Location</label>
                            <input type="text" id="location" name="location" class="w-full border border-gray-300 p-2 rounded-lg focus:ring focus:ring-blue-200 focus:outline-green-500" required>
                        </div>
                        <div class="flex">
                            <button type="submit" class="w-full bg-green-500 text-white px-4 py-2 rounded-lg shadow hover:bg-green-600">Create Farmer</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-white text-center p-4">
                <?php include 'includes/footer.php'; ?>
            </div>
        </div>
    </div>
</body>

</html>