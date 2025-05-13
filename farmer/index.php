<?php
// Start session to access user data
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION['isFarmer']) || $_SESSION['isFarmer'] !== true) {
    // If the user is not a farmer, redirect to login page
    $_SESSION['error'] = "You are not authorized to access this Farmer's app.";
    // Optionally, you can redirect to a different page or show an error message
    // For example, redirect to the login page
    header("Location: ../login.php");
    exit();
}

// Include database connection
require_once "../config/db.php";

// Fetch farmerId dynamically from the database using userId from session
$userId = $_SESSION['userId'];
// initilize farmer's Id
$farmerId = null;
$farmerIdQuery = "SELECT farmerId FROM farmers WHERE userId = ?";
$farmerIdStmt = $conn->prepare($farmerIdQuery);
$farmerIdStmt->bind_param("i", $userId);
$farmerIdStmt->execute();
$farmerIdResult = $farmerIdStmt->get_result();
if ($farmerIdResult->num_rows > 0) {
    $farmerId = $farmerIdResult->fetch_assoc()['farmerId'];
} else {
    // Handle case where no farmerId is found
    $_SESSION['error'] = "Farmer profile not found.";
    header("Location: ../login.php");
    exit();
}

$query = "
    SELECT 
        u.fullName, u.email, u.phoneNumber, u.isActive, 
        f.location, f.totalQtySold, f.totalOutstandingBalance, 
        f.totalAmountEarned, f.totalAmountPaid 
    FROM users u 
    INNER JOIN farmers f ON u.userId = f.userId 
    WHERE f.userId = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$farmerDetails = $stmt->get_result()->fetch_assoc();

// Fetch sale records
$saleQuery = "SELECT date, quantity, unitRate, totalAmount FROM sale_records WHERE farmerId = ?";
$saleStmt = $conn->prepare($saleQuery);
$saleStmt->bind_param("i", $farmerId);
$saleStmt->execute();
$saleRecords = $saleStmt->get_result();

// Fetch payment records
$paymentQuery = "SELECT date, amountPaid, totalOutstandingBalance FROM payments WHERE farmerId = ?";
$paymentStmt = $conn->prepare($paymentQuery);
$paymentStmt->bind_param("i", $farmerId);
$paymentStmt->execute();
$paymentRecords = $paymentStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Dashboard</title>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- Tailwind CSS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php include '../admin/includes/sidebar.php'; ?>
        <!-- Main Content -->
        <div id="main-content" class="flex-1 flex flex-col">
            <!-- Topbar -->
            <div class="bg-white shadow flex justify-between items-center">
                <?php include '../admin/includes/topbar.php'; ?>
            </div>

            <!-- Main Section -->
            <div class="flex-1 p-6">
                <!-- Farmer Details Section -->
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-gray-800">Welcome,
                            <?= htmlspecialchars($farmerDetails['fullName']) ?>!</h1>
                        <p class="text-gray-600">Here is an overview of your profile and activities.</p>
                    </div>
                </div>
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">My Profile</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-600">Full Name: <span
                                    class="font-semibold"><?= $farmerDetails['fullName'] ?></span></p>
                            <p class="text-gray-600">Email: <span
                                    class="font-semibold"><?= $farmerDetails['email'] ?></span></p>
                            <p class="text-gray-600">Phone Number: <span
                                    class="font-semibold"><?= $farmerDetails['phoneNumber'] ?></span></p>
                        </div>
                        <div>
                            <p class="text-gray-600">Active Status: <span
                                    class="font-semibold <?= $farmerDetails['isActive'] ? 'text-green-500' : 'text-red-500' ?>">
                                    <?= $farmerDetails['isActive'] ? 'Active' : 'Inactive' ?></span>
                            </p>
                            <p class="text-gray-600">Location: <span
                                    class="font-semibold"><?= $farmerDetails['location'] ?></span></p>
                        </div>
                    </div>
                </div>

                <!-- Stats Section -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white shadow rounded-lg p-6 flex items-center">
                        <div class="text-blue-500 text-4xl mr-4">
                            <i class="fas fa-weight"></i>
                        </div>
                        <div>
                            <l class="text-lg font-semibold">Total Quantity Delivered</l>
                            <p class="text-2xl font-bold"><?= $farmerDetails['totalQtySold'] ?> kg</p>
                        </div>
                    </div>

                    <div class="bg-white shadow rounded-lg p-6 flex items-center">
                        <div class="text-green-500 text-4xl mr-4">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Total Earnings</h3>
                            <p class="text-2xl font-bold">KES
                                <?= number_format($farmerDetails['totalAmountEarned'], 2) ?>
                            </p>
                        </div>
                    </div>

                    <div class="bg-white shadow rounded-lg p-6 flex items-center">
                        <div class="text-red-500 text-4xl mr-4">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Total Outstanding Balance</h3>
                            <p class="text-2xl font-bold">KES
                                <?= number_format($farmerDetails['totalOutstandingBalance'], 2) ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Transaction History -->
                <div>
                    <h2 class="text-xl font-bold mb-4">Transaction History</h2>

                    <!-- Filter Form -->
                    <!-- Tea Sale History -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Tea Delivery History</h3>
                        <div class="space-y-4">
                            <?php while ($sale = $saleRecords->fetch_assoc()): ?>
                                <div class="bg-white shadow rounded-lg p-4 flex justify-between items-center">
                                    <div>
                                        <p class="text-gray-600">Date: <span
                                                class="font-semibold"><?= $sale['date'] ?></span></p>
                                        <p class="text-gray-600">Quantity Delivered: <span
                                                class="font-semibold"><?= $sale['quantity'] ?> kg</span></p>
                                        <p class="text-gray-600">Unit Rate: <span class="font-semibold">KES
                                                <?= number_format($sale['unitRate'], 2) ?></span></p>
                                        <p class="text-gray-600">Total Amount: <span class="font-semibold">KES
                                                <?= number_format($sale['totalAmount'], 2) ?></span></p>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <!-- Payment History -->
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Payment History</h3>
                        <div class="space-y-4">
                            <?php while ($payment = $paymentRecords->fetch_assoc()): ?>
                                <div class="bg-white shadow rounded-lg p-4 flex justify-between items-center">
                                    <div>
                                        <p class="text-gray-600">Date: <span
                                                class="font-semibold"><?= $payment['date'] ?></span></p>
                                        <p class="text-gray-600">Amount Paid: <span class="font-semibold">KES
                                                <?= number_format($payment['amountPaid'], 2) ?></span></p>
                                        <p class="text-gray-600">Outstanding Balance: <span class="font-semibold">KES
                                                <?= number_format($payment['totalOutstandingBalance'], 2) ?></span></p>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    <!-- Footer -->
                    <div class="text-white text-center p-4">
                        <?php include '../admin/includes/footer.php'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>