<?php
$pageTitle = "Register";
include 'include/header.php';
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $location = $_POST['Location'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); // "s" indicates the parameter is a string
    $stmt->execute();
    $stmt->bind_result($email_count);
    $stmt->fetch();
    $stmt->close(); // Close the first statement to avoid "Commands out of sync" error

    if ($email_count > 0) {
        echo "Email already exists. Please use a different email.";
    } else {
        // Insert farmer data into the database
        $stmt = $conn->prepare("INSERT INTO users (Full_name, email, Contact, `Location`, `password`) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("sssss", $name, $email, $contact, $location, $password);
        if ($stmt->execute()) {
            // Redirect to the home page after successful registration
            header("Location: index.php");
            exit();
        } else {
            echo "An error occurred during registration. Please try again later.";
        }
        $stmt->close(); // Close the second statement
    }
}
?>


<div class="container">
    <h2 class="FR">Farmer Registration</h2>
    <form class="registration-form" method="POST" action="register.php">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="Location">Location:</label>
        <input type="text" id="Location" name="Location" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="contact">Contact:</label>
        <input type="tel" id="contact" name="contact" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" class="btn">Register</button>
    </form>
</div>

<?php include 'include/footer.php'; ?>