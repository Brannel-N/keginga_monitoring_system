<?php

class User
{
    private $conn;

    public function __construct($connection)
    {
        echo "User model initialized.<br>";
        $this->conn = $connection;

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        // create table
        $this->createTable();
    }

    // Method to create the users table
    public function createTable()
    {
        echo "Creating users table...<br>";
        $sql = "CREATE TABLE IF NOT EXISTS users (
            userId INT AUTO_INCREMENT PRIMARY KEY,
            fullName VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            phoneNumber VARCHAR(20),
            createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            isActive TINYINT(1) DEFAULT 1,
            isAdmin TINYINT(1) DEFAULT 0
        )";

        if ($this->conn->query($sql) === TRUE) {
            echo "Table 'users' created successfully.";
        } else {
            echo "Error creating table: " . $this->conn->error;
        }
    }

    // Create a new user
    public function createUser($fullName, $email, $password, $phoneNumber, $isActive = 1, $isAdmin = 0)
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("INSERT INTO users (fullName, email, password, phoneNumber, isActive, isAdmin) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssii", $fullName, $email, $hashedPassword, $phoneNumber, $isActive, $isAdmin);

        if ($stmt->execute()) {
            $userId = $stmt->insert_id; // Get the last inserted ID
            $stmt->close();
            return $userId;
        } else {
            $error = $stmt->error;
            $stmt->close();
            throw new Exception("Error creating user: " . $error);
        }
    }

    // Read user by ID
    public function getUserById($userId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE userId = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    // Update user
    public function updateUser($userId, $fullName, $email, $phoneNumber, $isActive, $isAdmin)
    {
        $stmt = $this->conn->prepare("UPDATE users SET fullName = ?, email = ?, phoneNumber = ?, isActive = ?, isAdmin = ? WHERE userId = ?");
        $stmt->bind_param("sssiii", $fullName, $email, $phoneNumber, $isActive, $isAdmin, $userId);

        if ($stmt->execute()) {
            echo "User updated successfully.";
        } else {
            echo "Error updating user: " . $stmt->error;
        }

        $stmt->close();
    }

    // Delete user
    public function deleteUser($userId)
    {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE userId = ?");
        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) {
            echo "User deleted successfully.";
        } else {
            echo "Error deleting user: " . $stmt->error;
        }

        $stmt->close();
    }

    // Get all users
    public function getAllUsers()
    {
        $sql = "SELECT * FROM users";
        $result = $this->conn->query($sql);

        $users = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }

        return $users;
    }
}
