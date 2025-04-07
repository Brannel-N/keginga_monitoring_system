<?php

class Rate
{
    private $conn;
    private $table = "rates";

    public function __construct($connection)
    {
        $this->conn = $connection;

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        // create table
        $this->createTable();
    }

    // Create the rates table
    public function createTable()
    {
        $query = "CREATE TABLE IF NOT EXISTS " . $this->table . " (
            rateId INT AUTO_INCREMENT PRIMARY KEY,
            startDate DATE NOT NULL,
            endDate DATE NOT NULL,
            unitRate DECIMAL(10, 2) NOT NULL
        )";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }

    // Create a new rate
    public function createRate($startDate, $endDate, $unitRate)
    {
        $query = "INSERT INTO " . $this->table . " (startDate, endDate, unitRate) VALUES (:startDate, :endDate, :unitRate)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':endDate', $endDate);
        $stmt->bindParam(':unitRate', $unitRate);

        return $stmt->execute();
    }

    // Read all rates
    public function readRate()
    {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Read a single rate by ID
    public function readRateById($rateId)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE rateId = :rateId";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':rateId', $rateId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update a rate
    public function updateRate($rateId, $startDate, $endDate, $unitRate)
    {
        $query = "UPDATE " . $this->table . " SET startDate = :startDate, endDate = :endDate, unitRate = :unitRate WHERE rateId = :rateId";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':rateId', $rateId);
        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':endDate', $endDate);
        $stmt->bindParam(':unitRate', $unitRate);

        return $stmt->execute();
    }

    // Delete a rate
    public function deleteRate($rateId)
    {
        $query = "DELETE FROM " . $this->table . " WHERE rateId = :rateId";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':rateId', $rateId);

        return $stmt->execute();
    }
}
