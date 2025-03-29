<?php
session_start();
include 'include/db.php';

// Redirect if not logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all suppliers
$suppliers = $conn->query("SELECT * FROM suppliers")->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission for adding a new supplier
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_supplier'])) {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];

    try {
        // Insert supplier data into the database
        $stmt = $conn->prepare("INSERT INTO suppliers (name, contact, email) VALUES (?, ?, ?)");
        $stmt->execute([$name, $contact, $email]);

        // Redirect to the suppliers page after successful submission
        header("Location: suppliers.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Handle supplier deletion
if (isset($_GET['delete_supplier'])) {
    $supplier_id = $_GET['delete_supplier'];

    try {
        // Delete supplier from the database
        $stmt = $conn->prepare("DELETE FROM suppliers WHERE id = ?");
        $stmt->execute([$supplier_id]);

        // Redirect to the suppliers page after successful deletion
        header("Location: suppliers.php");
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
    <title>Suppliers - Keginga Tea Farmers</title>
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Side Menu -->
        <aside class="side-menu">
            <div class="logo">
                <img src="./imgz/logo.png" alt="Keginga Tea Logo">
            </div>
            <nav>
                <ul>
                    <li><a href="admin.php">Dashboard</a></li>
                    <li><a href="suppliers.php" class="active">Suppliers</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header>
                <h1>Suppliers</h1>
            </header>

            <!-- Add Supplier Form -->
            <section class="form-section">
                <h2>Add New Supplier</h2>
                <form method="POST">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>

                    <label for="contact">Contact:</label>
                    <input type="text" id="contact" name="contact" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <button type="submit" name="add_supplier" class="btn">Add Supplier</button>
                </form>
            </section>

            <!-- Suppliers Table -->
            <section class="table-container">
                <h2>Supplier List</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($suppliers as $supplier): ?>
                            <tr>
                                <td><?php echo $supplier['id']; ?></td>
                                <td><?php echo $supplier['name']; ?></td>
                                <td><?php echo $supplier['contact']; ?></td>
                                <td><?php echo $supplier['email']; ?></td>
                                <td>
                                    <a href="edit_supplier.php?id=<?php echo $supplier['id']; ?>" class="btn-edit">Edit</a>
                                    <a href="suppliers.php?delete_supplier=<?php echo $supplier['id']; ?>" class="btn-delete">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>