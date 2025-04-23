<?php
// Start session to access user data
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../login.php");
    exit();
}

// Include database connection
require_once "../config/db.php";

// Fetch farmerId dynamically from GET parameter
if (isset($_GET['farmerId']) && is_numeric($_GET['farmerId'])) {
    $farmerId = intval($_GET['farmerId']);
} else {
    die("Invalid farmer ID.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $amountPaid = $_POST['amountPaid'];

    // Insert payment record
    $insertQuery = "INSERT INTO payments (farmerId, `date`, amountPaid, totalOutstandingBalance) VALUES (?, ?, ?, 0)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("isd", $farmerId, $date, $amountPaid);
    if ($stmt->execute()) {
        // Update farmers table
        $updateQuery = "
            UPDATE farmers 
            SET 
            totalAmountPaid = totalAmountPaid + ?, 
            totalOutstandingBalance = CASE 
                WHEN totalOutstandingBalance = 0 THEN totalAmountEarned - totalAmountPaid - ? 
                ELSE totalOutstandingBalance - ? 
            END
            WHERE farmerId = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("dddi", $amountPaid, $amountPaid, $amountPaid, $farmerId);
        if ($updateStmt->execute()) {
            $successMessage = "Payment recorded and farmer data updated successfully!";
            header("Location: ./farmer_details.php?farmerId=$farmerId");
        } else {
            $errorMessage = "Failed to update farmer data. Please try again.";
        }
    } else {
        $errorMessage = "Failed to record payment. Please try again.";
        echo "Error: {$stmt->error}";
        header("Location: ./new_payment.php?farmerId=$farmerId");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - New Payment</title>
    <!-- Tailwind CSS -->
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
            <div class="w-full lg:w-1/3 p-2 md:p-6 mx-auto">
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-bold mb-4">New Payment</h2>

                    <?php if (isset($successMessage)): ?>
                            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                                <?= $successMessage ?>
                            </div>
                    <?php endif; ?>

                    <?php if (isset($errorMessage)): ?>
                            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                                <?= $errorMessage ?>
                            </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="flex flex-col gap-4">
                            <div>
                                <label for="date" class="block text-gray-700">Date</label>
                                <input type="date" id="date" name="date" class="w-full border border-gray-300 rounded-lg p-2 focus:outline-green-400" required>
                            </div>
                            <div>
                                <label for="amountPaid" class="block text-gray-700">Amount Paid (KES)</label>
                                <input type="number" id="amountPaid" name="amountPaid" class="w-full border border-gray-300 rounded-lg p-2 focus:outline-green-400" step="0.01" required>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="w-full bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 focus:outline-none">
                                Save Payment
                            </button>
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
