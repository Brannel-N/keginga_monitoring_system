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

// Handle CSV export
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed.");
    }

    // Fetch all farmers from the database
    $sql = "SELECT id, name, address, phone, email FROM farmers";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="farmers_export_' . date('Y-m-d') . '.csv"');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Add CSV headers
        fputcsv($output, ['ID', 'Name', 'Address', 'Phone', 'Email']);

        // Add farmer data to CSV
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $row['id'],
                htmlspecialchars_decode($row['name']), // Decode HTML entities for CSV
                htmlspecialchars_decode($row['address']),
                htmlspecialchars_decode($row['phone']),
                htmlspecialchars_decode($row['email'])
            ]);
        }

        // Close output stream
        fclose($output);
        exit();
    } else {
        // No farmers found
        $_SESSION['error'] = "No farmers found to export.";
        header("Location: manage_farmers.php");
        exit();
    }
}

// Generate a CSRF token for the form
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Farmers - Keginga Farmers</title>
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <h1>Export Farmers</h1>

    <!-- Display error messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error"><?php echo htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8'); ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Export Form -->
    <form method="POST" action="export_farmers.php">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <button type="submit" class="button">Export Farmers to CSV</button>
    </form>

    <!-- Back to Manage Farmers -->
    <div style="margin-top: 20px;">
        <a href="manage_farmers.php" class="button">Back to Manage Farmers</a>
    </div>
</body>
</html>