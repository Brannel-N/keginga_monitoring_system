<?php
// Include database configuration
require_once '../config/db.php';

// Start session to access user data
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

// Fetch farmers from the database
$search = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
$query = "SELECT f.farmerId, u.fullName, u.email, u.phoneNumber, f.location, f.totalQtySold, f.totalOutstandingBalance, f.totalAmountEarned, f.totalAmountPaid 
          FROM farmers f 
          INNER JOIN users u ON f.userId = u.userId";
if (!empty($search)) {
    $query .= " WHERE LOWER(f.farmerId) LIKE ? OR LOWER(u.fullName) LIKE ?";
}

$stmt = $conn->prepare($query);
if (!empty($search)) {
    $searchParam = "%$search%";
    $stmt->bind_param("ss", $searchParam, $searchParam);
}
$stmt->execute();
$result = $stmt->get_result();
$farmers = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Farmers</title>
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
                <!-- Search Bar -->
                <div class="mb-6 flex items-center">
                    <form method="GET" action="" class="flex-1">
                        <div class="flex items-center">
                            <input type="text" name="search" placeholder="Search by Farmer ID or Name"
                                class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300"
                                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            <button type="submit"
                                class="ml-2 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none">
                                Search
                            </button>
                        </div>
                    </form>
                    <a href="create_farmer.php"
                        class="ml-4 bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 focus:outline-none">
                        Add Farmer
                    </a>
                </div>

                <!-- Farmers Table -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-bold mb-4">Farmers List</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border-collapse border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-300 px-4 py-2 text-left">Farmer ID</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Full Name</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Email</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Phone Number</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Location</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Total Qty Sold</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Outstanding Balance</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Total Amount Earned</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Total Amount Paid</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Display farmers
                                if (!empty($farmers)) {
                                    foreach ($farmers as $farmer) {
                                        echo "<tr>
                                            <td class='border border-gray-300 px-4 py-2'>{$farmer['farmerId']}</td>
                                            <td class='border border-gray-300 px-4 py-2'>{$farmer['fullName']}</td>
                                            <td class='border border-gray-300 px-4 py-2'>{$farmer['email']}</td>
                                            <td class='border border-gray-300 px-4 py-2'>{$farmer['phoneNumber']}</td>
                                            <td class='border border-gray-300 px-4 py-2'>{$farmer['location']}</td>
                                            <td class='border border-gray-300 px-4 py-2'>{$farmer['totalQtySold']}</td>
                                            <td class='border border-gray-300 px-4 py-2'>{$farmer['totalOutstandingBalance']}</td>
                                            <td class='border border-gray-300 px-4 py-2'>{$farmer['totalAmountEarned']}</td>
                                            <td class='border border-gray-300 px-4 py-2'>{$farmer['totalAmountPaid']}</td>
                                            <td class='border border-gray-300 px-4 py-2'>
                                                <a href='farmer_details.php?farmerId={$farmer['farmerId']}' 
                                                   class='text-blue-500 hover:underline'>View More</a>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr>
                                        <td colspan='10' class='border border-gray-300 px-4 py-2 text-center text-gray-500'>No farmers found</td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
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
