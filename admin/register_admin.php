<?php
require '../config/db.php';
// Start session to access user data
session_start();
// if (!isset($_SESSION['user'])) {
//     header("Location: login.php");
//     exit();
// }
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
if (isset($_SESSION['error'])) {
    unset($_SESSION['error']);
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input data
    $fullName = isset($_POST['fullName']) ? htmlspecialchars(trim($_POST['fullName'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $phoneNumber = isset($_POST['phoneNumber']) ? htmlspecialchars(trim($_POST['phoneNumber'])) : '';
    $password = isset($_POST['password']) ? htmlspecialchars(trim($_POST['password'])) : '';
    $isActive = 1; // Default value for isActive
    $isAdmin = 1; // Default value for isAdmin

    // Validate required fields
    if (empty($fullName) || empty($email) || empty($phoneNumber) || empty($password)) {
        $_SESSION['error'] = 'All fields are required.';
        exit;
    }

    // Create a new Farmer instance
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO users (fullName, email, password, phoneNumber, isActive, isAdmin) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssii", $fullName, $email, $hashedPassword, $phoneNumber, $isActive, $isAdmin);

    if ($stmt->execute()) {
        $userId = $stmt->insert_id; // Get the last inserted ID
        $stmt->close();
        header("Location: ./login.php");
    } else {
        $error = $stmt->error;
        echo "Error creating user: " . $error;
        // Check for duplicate email
        $stmt->close();
        return null;

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin</title>
</head>

<body>
    <form action="" method="POST" id="createAdminForm">
        <h6>Register Admin</h6>
        <div class="mb-4">
            <label for="fullName" class="block text-gray-700 font-medium mb-2">Full Name</label>
            <input type="text" id="fullName" name="fullName"
                class="w-full border border-gray-300 p-2 rounded-lg focus:ring focus:ring-blue-200 focus:outline-green-500"
                required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
            <input type="email" id="email" name="email"
                class="w-full border border-gray-300 p-2 rounded-lg focus:ring focus:ring-blue-200 focus:outline-green-500"
                required>
        </div>
        <div class="mb-4">
            <label for="phoneNumber" class="block text-gray-700 font-medium mb-2">Phone Number</label>
            <input type="text" id="phoneNumber" name="phoneNumber"
                class="w-full border border-gray-300 p-2 rounded-lg focus:ring focus:ring-blue-200 focus:outline-green-500"
                required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
            <input type="text" id="password" name="password"
                class="w-full border border-gray-300 p-2 rounded-lg focus:ring focus:ring-blue-200 focus:outline-green-500"
                required>
        </div>
        <div class="flex">
            <button type="submit"
                class="w-full bg-green-500 text-white px-4 py-2 rounded-lg shadow hover:bg-green-600">Submit</button>
        </div>
    </form>
</body>

</html>