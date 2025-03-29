<?php
session_start();
include '../include/db_connection.php'; // Include the database connection file

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php"); // Redirect to login if not authorized
    exit();
}

// Check if the farmer ID is provided
if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
    $farmer_id = intval($_GET['id']); // Sanitize the farmer ID

    try {
        // Delete the farmer from the database
        $stmt = $conn->prepare("DELETE FROM farmers WHERE id = ?");
        $stmt->execute([$farmer_id]);

        // Redirect back to the manage farmers page with a success message
        $_SESSION['message'] = "Farmer deleted successfully!";
        header("Location: manage_farmers.php");
        exit();
    } catch (PDOException $e) {
        // Handle any errors
        $_SESSION['error'] = "Error deleting farmer: " . $e->getMessage();
        header("Location: manage_farmers.php");
        exit();
    }
} else {
    // Redirect back if no valid farmer ID is provided
    $_SESSION['error'] = "Invalid farmer ID.";
    header("Location: manage_farmers.php");
    exit();
}
?>