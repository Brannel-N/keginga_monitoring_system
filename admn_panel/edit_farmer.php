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

// Validate and sanitize the farmer ID from the URL
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header("Location: manage_farmers.php");
    exit();
}

$farmer_id = intval($_GET['id']); // Ensure the ID is an integer

// Fetch the farmer's details from the database
$sql = "SELECT * FROM farmers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $farmer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // No farmer found with the given ID
    header("Location: manage_farmers.php");
    exit();
}

$farmer = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed.");
    }

    // Retrieve and sanitize form data
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $address = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }

    // Update the farmer's details in the database
    $update_sql = "UPDATE farmers SET name = ?, address = ?, phone = ?, email = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssi", $name, $address, $phone, $email, $farmer_id);

    if ($update_stmt->execute()) {
        // Redirect to the manage farmers page with a success message
        $_SESSION['message'] = "Farmer details updated successfully!";
        header("Location: manage_farmers.php");
        exit();
    } else {
        // Handle the error
        $error = "Error updating farmer details: " . $conn->error;
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
    <title>Edit Farmer - Keginga Farmers</title>
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <h1>Edit Farmer</h1>

    <!-- Display error messages -->
    <?php if (isset($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <!-- Edit Farmer Form -->
    <form method="POST" action="edit_farmer.php?id=<?php echo $farmer_id; ?>">
        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($farmer['name'], ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($farmer['address'], ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($farmer['phone'], ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($farmer['email'], ENT_QUOTES, 'UTF-8'); ?>" required>

        <button type="submit" class="button">Update Farmer</button>
    </form>

    <!-- Back to Manage Farmers -->
    <div style="margin-top: 20px;">
        <a href="manage_farmers.php" class="button">Back to Manage Farmers</a>
    </div>
</body>
</html>