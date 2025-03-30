<?php
// Start the session with secure settings
session_start([
    'cookie_lifetime' => 86400, // 1 day
    'cookie_secure'   => true,  // Only send cookies over HTTPS
    'cookie_httponly' => true,  // Prevent JavaScript access to cookies
    'use_strict_mode' => true   // Prevent session fixation attacks
]);

// Include the database connection file
include 'db_connection.php';

// Check if the user is logged in and has the necessary permissions
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle farmer deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_farmer'])) {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed.");
    }

    // Validate and sanitize the farmer ID
    $farmer_id = intval($_POST['farmer_id']);
    if ($farmer_id <= 0) {
        die("Invalid farmer ID.");
    }

    // Delete the farmer from the database
    $delete_sql = "DELETE FROM farmers WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $farmer_id);

    if ($delete_stmt->execute()) {
        $_SESSION['message'] = "Farmer deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting farmer: " . $conn->error;
    }

    // Redirect to avoid form resubmission
    header("Location: manage_farmers.php");
    exit();
}

// Fetch all farmers from the database
$sql = "SELECT id, name, address, phone, email FROM farmers";
$result = $conn->query($sql);

// Generate a CSRF token for the form
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Farmers - Keginga Farmers</title>
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <h1>Manage Farmers</h1>

    <!-- Display success or error messages -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="message"><?php echo htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8'); ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="error"><?php echo htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8'); ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Add Farmer Button -->
    <a href="add_farmer.php" class="button">Add New Farmer</a>

    <!-- Export Farmers Button -->
    <a href="export_farmers.php" class="button">Export Farmers</a>

    <!-- Farmers Table -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['address'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <!-- Edit Button -->
                            <a href="edit_farmer.php?id=<?php echo $row['id']; ?>" class="button">Edit</a>

                            <!-- Delete Form -->
                            <form method="POST" action="manage_farmers.php" style="display: inline;">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" name="farmer_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete_farmer" class="button delete" onclick="return confirm('Are you sure you want to delete this farmer?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No farmers found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Logout Button -->
    <div style="margin-top: 20px;">
        <a href="logout.php" class="button">Logout</a>
    </div>
</body>
</html>