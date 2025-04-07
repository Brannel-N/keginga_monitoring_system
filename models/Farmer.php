<?php

require_once 'User.php'; 

class Farmer
{
    private $conn;
    private $userModel;

    public function __construct($connection)
    {
        $this->conn = $connection;

        echo "Farmer model initialized.<br>";

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        // create table
        $this->createTable();

        // Initialize the User model
        $this->userModel = new User($connection);
    }

    // Method to create the farmers table and ensure the users table exists
    public function createTable()
    {
        echo "Creating farmers table...<br>";
        
        // Ensure the users table exists
        $this->userModel->createTable();

        $sql = "CREATE TABLE IF NOT EXISTS farmers (
            farmerId INT AUTO_INCREMENT PRIMARY KEY,
            userId INT NOT NULL UNIQUE,
            location VARCHAR(255) NOT NULL,
            totalQtySold DECIMAL(10, 2) DEFAULT 0,
            totalAmountEarned DECIMAL(10, 2) DEFAULT 0,
            totalAmountPaid DECIMAL(10, 2) DEFAULT 0,
            totalOutstandingBalance DECIMAL(10, 2) DEFAULT 0,
            FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE
        )";

        if ($this->conn->query($sql) === TRUE) {
            echo "Table 'farmers' created successfully.<br>";
        } else {
            echo "Error creating table: " . $this->conn->error . "<br>";
        }
    }

    // Method to create a new farmer record
    public function createFarmer($fullName, $email, $phoneNumber, $password,  $location)
    {
        // Create a user record first
        $userId = $this->userModel->createUser(
            $fullName,
            $email,
            $password,
            $phoneNumber
        );

        if ($userId) {
            $stmt = $this->conn->prepare("INSERT INTO farmers (userId, location) VALUES (?, ?)");
            $stmt->bind_param("is", $userId, $location);

            if ($stmt->execute()) {
                $farmerId = $stmt->insert_id; // Get the last inserted ID
                $stmt->close();
                return $farmerId;
            } else {
                $error = $stmt->error;
                echo "Error creating farmer record: " . $error;
                $stmt->close();
                return null;
            }
        }
    }

    // Method to read a farmer record by farmerId, including user details
    public function getFarmerById($farmerId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM farmers WHERE farmerId = ?");
        $stmt->bind_param("i", $farmerId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $farmer = $result->fetch_assoc();
            $user = $this->userModel->getUserById($farmer['userId']);
            $stmt->close();
            return array_merge($farmer, $user);
        } else {
            $stmt->close();
            return null;
        }
    }

    // Method to update a farmer record, including user details
    public function updateFarmer($farmerId, $farmerData, $userData)
    {
        // Update the farmer record
        $stmt = $this->conn->prepare("UPDATE farmers SET location = ?, totalQtySold = ?, totalAmountEarned = ?, totalAmountPaid = ?, totalOutstandingBalance = ? WHERE farmerId = ?");
        $stmt->bind_param(
            "sddddi",
            $farmerData['location'],
            $farmerData['totalQtySold'],
            $farmerData['totalAmountEarned'],
            $farmerData['totalAmountPaid'],
            $farmerData['totalOutstandingBalance'],
            $farmerId
        );

        if ($stmt->execute()) {
            echo "Farmer record updated successfully.";
        } else {
            echo "Error updating farmer record: " . $stmt->error;
        }

        $stmt->close();

        // Update the user record
        $farmer = $this->getFarmerById($farmerId);
        if ($farmer) {
            $this->userModel->updateUser($farmer['userId'], $userData['fullName'], $userData['email'], $userData['phoneNumber'], $userData['isActive'], $userData['isAdmin']);
        }
    }

    // Method to delete a farmer record
    public function deleteFarmer($farmerId)
    {
        $farmer = $this->getFarmerById($farmerId);
        if ($farmer) {
            // Delete the farmer record
            $stmt = $this->conn->prepare("DELETE FROM farmers WHERE farmerId = ?");
            $stmt->bind_param("i", $farmerId);

            if ($stmt->execute()) {
                echo "Farmer record deleted successfully.";
            } else {
                echo "Error deleting farmer record: " . $stmt->error;
            }

            $stmt->close();

            // Delete the associated user record
            $this->userModel->deleteUser($farmer['userId']);
        }
    }

    // Method to get all farmers, including user details
    public function getAllFarmers()
    {
        $sql = "SELECT * FROM farmers";
        $result = $this->conn->query($sql);

        $farmers = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user = $this->userModel->getUserById($row['userId']);
                $farmers[] = array_merge($row, $user);
            }
        }

        return $farmers;
    }
}
?>