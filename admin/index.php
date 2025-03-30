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
    <title>Admin Panel</title>
    <!-- Tailwind CSS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            sidebar.classList.toggle('hidden');
            mainContent.classList.toggle('w-full');
        }
    </script>
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
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Card 1: Number of Farmers -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="text-blue-500 text-4xl">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-bold">Number of Farmers</h2>
                                <p class="text-gray-600 text-xl">120</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Total Kilograms of Tea -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="text-green-500 text-4xl">
                                <i class="fas fa-leaf"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-bold">Total Kilograms of Tea</h2>
                                <p class="text-gray-600 text-xl">15,000 kg</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Total Transactions -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="text-yellow-500 text-4xl">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-bold">Total Transactions</h2>
                                <p class="text-gray-600 text-xl">350</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions Table -->
                <div class="mt-8 bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-bold mb-4">Recent Tea Sale Transactions</h2>
                    <table class="min-w-full table-auto border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-4 py-2 text-left">Farmer Name</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Date & Time</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Kilograms Bought</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">John Doe</td>
                                <td class="border border-gray-300 px-4 py-2">2023-03-01 10:30 AM</td>
                                <td class="border border-gray-300 px-4 py-2">50 kg</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">Jane Smith</td>
                                <td class="border border-gray-300 px-4 py-2">2023-03-01 11:00 AM</td>
                                <td class="border border-gray-300 px-4 py-2">30 kg</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">Michael Brown</td>
                                <td class="border border-gray-300 px-4 py-2">2023-03-01 11:30 AM</td>
                                <td class="border border-gray-300 px-4 py-2">40 kg</td>
                            </tr>
                        </tbody>
                    </table>
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