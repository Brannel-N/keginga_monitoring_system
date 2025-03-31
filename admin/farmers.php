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
                <div class="mb-6">
                    <form method="GET" action="">
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
                                    <th class="border border-gray-300 px-4 py-2 text-left">Contact</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Location</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Joined</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Sample data for demonstration
                                $farmers = [
                                    ['id' => 'F001', 'name' => 'John Doe', 'phone' => '123-456-7890', 'location' => 'Nairobi'],
                                    ['id' => 'F002', 'name' => 'Jane Smith', 'phone' => '987-654-3210', 'location' => 'Mombasa'],
                                    ['id' => 'F003', 'name' => 'Michael Brown', 'phone' => '555-555-5555', 'location' => 'Kisumu'],
                                ];

                                // Filter farmers based on search query
                                $search = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
                                $filteredFarmers = array_filter($farmers, function ($farmer) use ($search) {
                                    return empty($search) || strpos(strtolower($farmer['id']), $search) !== false || strpos(strtolower($farmer['name']), $search) !== false;
                                });

                                // Display farmers
                                if (!empty($filteredFarmers)) {
                                    foreach ($filteredFarmers as $farmer) {
                                        echo "<tr>
                                            <td class='border border-gray-300 px-4 py-2'>{$farmer['id']}</td>
                                            <td class='border border-gray-300 px-4 py-2'>{$farmer['name']}</td>
                                            <td class='border border-gray-300 px-4 py-2'>{$farmer['phone']}</td>
                                            <td class='border border-gray-300 px-4 py-2'>{$farmer['location']}</td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr>
                                        <td colspan='4' class='border border-gray-300 px-4 py-2 text-center text-gray-500'>No farmers found</td>
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