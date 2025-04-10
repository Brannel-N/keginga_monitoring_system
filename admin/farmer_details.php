<?php
// Start session to access user data
// session_start();
// if (!isset($_SESSION['user'])) {
//     header("Location: login.php");
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Farmer Details</title>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- Tailwind CSS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen">
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
                <!-- Farmer Details Section -->
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">Farmer Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-600">Farmer ID: <span class="font-semibold">12345</span></p>
                            <p class="text-gray-600">Full Name: <span class="font-semibold">John Doe</span></p>
                            <p class="text-gray-600">Email: <span class="font-semibold">johndoe@example.com</span></p>
                        </div>
                        <div>
                            <p class="text-gray-600">Phone Number: <span class="font-semibold">+1234567890</span></p>
                            <p class="text-gray-600">Active Status: <span
                                    class="font-semibold text-green-500">Active</span>
                            </p>
                            <p class="text-gray-600">Location: <span class="font-semibold">Nairobi, Kenya</span></p>
                        </div>
                    </div>
                </div>

                <!-- Cards Section -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Total Quantity Sold Card -->
                    <div class="bg-white shadow rounded-lg p-6 flex items-center">
                        <div class="text-blue-500 text-4xl mr-4">
                            <i class="fas fa-weight"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Total Quantity Sold</h3>
                            <p class="text-2xl font-bold">0 kg</p>
                        </div>
                    </div>

                    <!-- Total Earnings Card -->
                    <div class="bg-white shadow rounded-lg p-6 flex items-center">
                        <div class="text-green-500 text-4xl mr-4">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Total Earnings</h3>
                            <p class="text-2xl font-bold">KES 0.00</p>
                        </div>
                    </div>

                    <!-- Total Outstanding Balance Card -->
                    <div class="bg-white shadow rounded-lg p-6 flex items-center">
                        <div class="text-red-500 text-4xl mr-4">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Total Outstanding Balance</h3>
                            <p class="text-2xl font-bold">KES 0.00</p>
                        </div>
                    </div>
                </div>

                <!-- Transaction History -->
                <div>
                    <h2 class="text-xl font-bold mb-4">Transaction History</h2>

                    <!-- Tea Sale History -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Tea Sale History</h3>
                        <div class="space-y-4">
                            <!-- Example Tea Sale Card -->
                            <div class="bg-white shadow rounded-lg p-4 flex justify-between items-center">
                                <div>
                                    <p class="text-gray-600">Date: <span class="font-semibold">2023-01-01</span></p>
                                    <p class="text-gray-600">Quantity Sold: <span class="font-semibold">50 kg</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Done to Farmer History -->
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Payment Done to Farmer History</h3>
                        <div class="space-y-4">
                            <!-- Example Payment Card -->
                            <div class="bg-white shadow rounded-lg p-4 flex justify-between items-center">
                                <div>
                                    <p class="text-gray-600">Date: <span class="font-semibold">2023-01-01</span></p>
                                    <p class="text-gray-600">Amount Paid: <span class="font-semibold">KES 100.00</span></p>
                                    <p class="text-gray-600">Outstanding Balance: <span
                                            class="font-semibold">KES 50.00</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
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