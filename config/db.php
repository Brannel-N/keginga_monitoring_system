<?php
$host = 'localhost';
$dbname = 'jean';
$username = 'root';
$password = '';

// Create a new MySQLi connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected to the database successfully!";
}
