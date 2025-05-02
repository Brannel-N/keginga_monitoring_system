<?php
// filepath: c:\xampp\htdocs\JEAN\processes\contact_form_handler.php

// Include database connection
include '../config/db.php'; // Adjust the path to your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validate form data
    if (empty($name) || empty($email) || empty($message)) {
        echo "All fields are required.";
        exit();
    }

    // Insert the data into the database
    $stmt = $conn->prepare("INSERT INTO feedback (name, email, message, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        // Redirect to a thank-you page or display a success message
        header("Location: thank_you.php?status=success");
        exit();
    } else {
        echo "An error occurred while submitting your message. Please try again later.";
        exit();
    }
} else {
    // Redirect to the contact form if accessed directly
    header("Location: ../index.php");
    exit();
}
?>
