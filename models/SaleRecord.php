<?php
class SaleRecord
{
    private $conn;
    private $farmerModel;

    public function __construct($connection)
    {
        $this->conn = $connection;

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        // Create the sale_records table
        $this->createTable();

        // Initialize the Farmer model
        $this->farmerModel = new Farmer($connection);
    }

    // Method to create the sale_records table
    public function createTable()
    {
        // Ensure the farmers table exists
        $this->farmerModel->createTable();

        $sql = "CREATE TABLE IF NOT EXISTS sale_records (
            recordId INT AUTO_INCREMENT PRIMARY KEY,
            farmerId INT NOT NULL,
            date DATE NOT NULL,
            quantity DECIMAL(10, 2) NOT NULL,
            unitRate DECIMAL(10, 2) NOT NULL,
            totalAmount DECIMAL(10, 2) GENERATED ALWAYS AS (quantity * unitRate) STORED,
            FOREIGN KEY (farmerId) REFERENCES farmers(farmerId) ON DELETE CASCADE
        )";

        if ($this->conn->query($sql) === TRUE) {
            echo "Table 'sale_records' created successfully.";
        } else {
            echo "Error creating table: " . $this->conn->error;
        }
    }

    // Create a new sale record
    public function createRecord($farmerId, $date, $quantity, $unitRate)
    {
        $sql = "INSERT INTO sale_records (farmerId, date, quantity, unitRate) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isdd", $farmerId, $date, $quantity, $unitRate);

        if ($stmt->execute()) {
            // Update farmer's totals
            $this->updateFarmerTotals($farmerId);
            echo "Sale record created successfully.";
        } else {
            echo "Error creating sale record: " . $stmt->error;
        }

        $stmt->close();
    }

    // Read sale records with farmer details
    public function readRecords()
    {
        $sql = "SELECT sr.*, f.farmerId, u.fullName 
                FROM sale_records sr
                JOIN farmers f ON sr.farmerId = f.farmerId
                JOIN users u ON f.userId = u.userId";

        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    // Update a sale record
    public function updateRecord($recordId, $date, $quantity, $unitRate)
    {
        $sql = "UPDATE sale_records SET date = ?, quantity = ?, unitRate = ? WHERE recordId = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sddi", $date, $quantity, $unitRate, $recordId);

        if ($stmt->execute()) {
            // Get the farmerId for the updated record
            $farmerId = $this->getFarmerIdByRecordId($recordId);
            // Update farmer's totals
            $this->updateFarmerTotals($farmerId);
            echo "Sale record updated successfully.";
        } else {
            echo "Error updating sale record: " . $stmt->error;
        }

        $stmt->close();
    }

    // Delete a sale record
    public function deleteRecord($recordId)
    {
        // Get the farmerId for the record to be deleted
        $farmerId = $this->getFarmerIdByRecordId($recordId);

        $sql = "DELETE FROM sale_records WHERE recordId = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $recordId);

        if ($stmt->execute()) {
            // Update farmer's totals
            $this->updateFarmerTotals($farmerId);
            echo "Sale record deleted successfully.";
        } else {
            echo "Error deleting sale record: " . $stmt->error;
        }

        $stmt->close();
    }

    // Helper method to update farmer's totals
    private function updateFarmerTotals($farmerId)
    {
        $sql = "SELECT SUM(quantity) AS totalQtySold, SUM(quantity * unitRate) AS totalAmountEarned 
                FROM sale_records WHERE farmerId = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $farmerId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        $totalQtySold = $result['totalQtySold'] ?? 0;
        $totalAmountEarned = $result['totalAmountEarned'] ?? 0;

        $updateSql = "UPDATE farmers SET totalQtySold = ?, totalAmountEarned = ? WHERE farmerId = ?";
        $updateStmt = $this->conn->prepare($updateSql);
        $updateStmt->bind_param("ddi", $totalQtySold, $totalAmountEarned, $farmerId);
        $updateStmt->execute();

        $stmt->close();
        $updateStmt->close();
    }

    // Helper method to get farmerId by recordId
    private function getFarmerIdByRecordId($recordId)
    {
        $sql = "SELECT farmerId FROM sale_records WHERE recordId = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $recordId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result['farmerId'] ?? null;
    }
}
?>