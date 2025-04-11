<?php
// Start session to access user data
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
require_once "../config/db.php";


// Fetch sales data from the database
$query = "SELECT 
            sale_records.recordId AS id, 
            sale_records.date, 
            sale_records.farmerId, 
            sale_records.quantity, 
            users.fullName AS farmer_name
          FROM sale_records 
          JOIN farmers ON sale_records.farmerId = farmers.farmerId 
          JOIN users ON farmers.userId = users.userId";
$result = $conn->query($query);

if (!$result) {
    die("Error fetching sales data: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tea Sales</title>
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
                <!-- Sales Table -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-bold mb-4">Sales History</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border-collapse border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Date</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Farmer ID</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Farmer Name</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Quantity</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Actions</th>
                                </tr>
                            </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['date']); ?></td>
                                    <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['farmerId']); ?></td>
                                    <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['farmer_name']); ?></td>
                                    <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['quantity']); ?></td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <a href="edit_sale.php?id=<?php echo urlencode($row['id']); ?>" class="text-blue-500 hover:underline">Edit</a> |
                                        <a href="delete_sale.php?id=<?php echo urlencode($row['id']); ?>" class="text-red-500 hover:underline" onclick="return confirm('Are you sure you want to delete this sale?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody></tr>
                        </table>
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