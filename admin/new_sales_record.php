<?php
// Start session to access user data
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
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
    $quantity = $_POST['quantity'];
    $unitRate = $_POST['unitRate'];

    $insertQuery = "INSERT INTO sale_records (farmerId, `date`, quantity, unitRate) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("isdd", $farmerId, $date, $quantity, $unitRate);
    if ($stmt->execute()) {
        // Update farmers table
        $updateQuery = "
            UPDATE farmers 
            SET 
                totalQtySold = totalQtySold + ?, 
                totalAmountEarned = totalAmountEarned + (? * ?)
            WHERE farmerId = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $totalAmountEarned = $quantity * $unitRate;
        $updateStmt->bind_param("dddi", $quantity, $quantity, $unitRate, $farmerId);
        if ($updateStmt->execute()) {
            $successMessage = "Sale record added and farmer data updated successfully!";
            header("Location: ../sales_records.php?farmerId=$farmerId");
        } else {
            $errorMessage = "Failed to update farmer data. Please try again.";
        }
    } else {
        $errorMessage = "Failed to add sale record. Please try again.";
        echo "Error: {$stmt->error}";
        header("Location: ../new_sales_record.php?farmerId=$farmerId");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - New Sales Record</title>
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
            <div class="w-full lg:w-1/3 p-6 mx-auto">
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-bold mb-4">New Sales Record</h2>

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
                                <label for="quantity" class="block text-gray-700">Quantity (kg)</label>
                                <input type="number" id="quantity" name="quantity" class="w-full border border-gray-300 rounded-lg p-2 focus:outline-green-400" step="0.01" required>
                            </div>
                            <div>
                                <label for="unitRate" class="block text-gray-700">Unit Rate (KES)</label>
                                <input type="number" id="unitRate" name="unitRate" class="w-full border border-gray-300 rounded-lg p-2 focus:outline-green-400" step="0.01" required>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="w-full bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 focus:outline-none">
                                Save Record
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
