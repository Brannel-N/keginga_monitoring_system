<?php
// Start session to access user data
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../login.php");
    exit();
}

// Include database connection
require_once '../config/db.php';

// Fetch stats from the database
$totalFarmers = 0;
$totalKilograms = 0;
$totalTransactions = 0;
$recentTransactions = [];

try {
    // Prepare and execute query to get total number of farmers
    $query = "SELECT COUNT(*) AS totalFarmers FROM farmers";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $totalFarmers = $result->fetch_assoc()['totalFarmers'];

    // Prepare and execute query to get total kilograms of tea
    $query = "SELECT SUM(quantity) AS totalKilograms FROM sale_records";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $totalKilograms = $result->fetch_assoc()['totalKilograms'];

    // Prepare and execute query to get total transactions
    $query = "SELECT COUNT(*) AS totalTransactions FROM sale_records";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $totalTransactions = $result->fetch_assoc()['totalTransactions'];

    // Prepare and execute query to get the 5 most recent transactions
    $query = "SELECT 
            sale_records.recordId AS id, 
            sale_records.date, 
            sale_records.quantity,
            users.fullName AS farmer_name
          FROM sale_records 
          JOIN farmers ON sale_records.farmerId = farmers.farmerId 
          JOIN users ON farmers.userId = users.userId ORDER BY `date` DESC LIMIT 5";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $recentTransactions = $result->fetch_all(MYSQLI_ASSOC);
} catch (mysqli_sql_exception $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
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
            <div class="flex-1 p-2 md:p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="text-blue-500 text-4xl">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-bold">Number of Farmers</h2>
                                <p class="text-gray-600 text-xl"><?php echo $totalFarmers; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="text-green-500 text-4xl">
                                <i class="fas fa-leaf"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-bold">Total Kilograms of Tea</h2>
                                <p class="text-gray-600 text-xl"><?php echo number_format($totalKilograms); ?> kg</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="text-yellow-500 text-4xl">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-bold">Total Transactions</h2>
                                <p class="text-gray-600 text-xl"><?php echo $totalTransactions; ?></p>
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
                            <?php foreach ($recentTransactions as $transaction): ?>
                                <tr>
                                    <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($transaction['farmer_name']); ?></td>
                                    <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($transaction['date']); ?></td>
                                    <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($transaction['quantity']); ?> kg</td>
                                </tr>
                            <?php endforeach; ?>
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