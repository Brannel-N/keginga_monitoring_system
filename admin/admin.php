<?php
session_start();
include '../include/db_connection.php'; // Updated path
include '../include/header.php'; // Updated path

// Redirect if not logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php"); // Updated path
    exit();
}

// Fetch all farmers
$farmers = $conn->query("SELECT * FROM farmers")->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission for recording weights
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['record_weight'])) {
    $farmer_id = $_POST['farmer_id'];
    $kilograms = $_POST['kilograms'];
    $date = date('Y-m-d'); // Current date

    try {
        // Insert weight data into the database
        $stmt = $conn->prepare("INSERT INTO farmer_weights (farmer_id, date, kilograms) VALUES (?, ?, ?)");
        $stmt->execute([$farmer_id, $date, $kilograms]);

        // Redirect to the admin page after successful submission
        header("Location: admin.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Handle farmer deletion
if (isset($_GET['delete_farmer'])) {
    $farmer_id = $_GET['delete_farmer'];

    try {
        // Delete farmer from the database
        $stmt = $conn->prepare("DELETE FROM farmers WHERE id = ?");
        $stmt->execute([$farmer_id]);

        // Redirect to the admin page after successful deletion
        header("Location: admin.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Keginga Tea Farmers</title>
    <link rel="stylesheet" href="../include/header.css"> <!-- Updated path -->
</head>
<body>
    <section class="dashboard-container">
        <aside class="side-menu">
            <div class="logo">
                <img src="imgz/user.png" alt="Keginga Tea Logo"> <!-- Updated path -->
            </div>
            <nav>
                <ul>
                    <li><a href="../index.php">Home</a></li> <!-- Updated path -->
                    <li><a href="admin.php" class="active">Dashboard</a></li>
                    <li><a href="manage_farmers.php">Manage Farmers</a></li>
                    <li><a href="../logout.php">Logout</a></li> <!-- Updated path -->
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <h1>Admin Dashboard</h1>
            <section class="cards">
                <div class="card">
                    <h3>Total Farmers</h3>
                    <p><?php echo count($farmers); ?></p>
                </div>
                <div class="card">
                    <h3>Total Kgs Supplied (This Month)</h3>
                    <p>
                        <?php
                        $current_month = date('Y-m');
                        $stmt = $conn->prepare("SELECT SUM(kilograms) AS total_kgs FROM farmer_weights WHERE date LIKE ?");
                        $stmt->execute(["$current_month%"]);
                        $total_kgs = $stmt->fetch(PDO::FETCH_ASSOC)['total_kgs'];
                        echo $total_kgs ? $total_kgs . ' kg' : '0 kg';
                        ?>
                    </p>
                </div>
            </section>
            <section class="form-section">
                <h2>Record Daily Weight</h2>
                <form method="POST">
                    <label for="farmer_id">Farmer:</label>
                    <select id="farmer_id" name="farmer_id" required>
                        <?php foreach ($farmers as $farmer): ?>
                            <option value="<?php echo $farmer['id']; ?>"><?php echo $farmer['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="kilograms">Kilograms:</label>
                    <input type="number" id="kilograms" name="kilograms" step="0.01" required>
                    <button type="submit" name="record_weight" class="btn">Record Weight</button>
                </form>
            </section>
            <section class="table-container">
                <h2>Farmers</h2>
                <a href="export_farmers.php" class="btn-export">Export Farmers</a>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($farmers as $farmer): ?>
                            <tr>
                                <td><?php echo $farmer['id']; ?></td>
                                <td><?php echo $farmer['name']; ?></td>
                                <td><?php echo $farmer['email']; ?></td>
                                <td><?php echo $farmer['contact']; ?></td>
                                <td>
                                    <a href="edit_farmer.php?id=<?php echo $farmer['id']; ?>" class="btn-edit">Edit</a>
                                    <a href="delete_farmer.php?id=<?php echo $farmer['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this farmer?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </section>
</body>
</html>