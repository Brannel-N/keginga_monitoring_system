<?php

class Payment
{
    private $conn;

    public function __construct($connection)
    {
        $this->conn = $connection;

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        // create table
        $this->createTable();
    }

    // Method to create the payments table
    public function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS payments (
            paymentId INT AUTO_INCREMENT PRIMARY KEY,
            farmerId INT NOT NULL,
            date DATE NOT NULL,
            amountPaid DECIMAL(10, 2) NOT NULL,
            totalOutstandingBalance DECIMAL(10, 2) NOT NULL,
            FOREIGN KEY (farmerId) REFERENCES farmers(farmerId) ON DELETE CASCADE
        )";

        if ($this->conn->query($sql) === TRUE) {
            echo "Table 'payments' created successfully.";
        } else {
            echo "Error creating table: " . $this->conn->error;
        }
    }

    // Create a new payment
    public function createPayment($farmerId, $date, $amountPaid)
    {
        // Start transaction
        $this->conn->begin_transaction();

        try {
            // Insert payment record
            $sql = "INSERT INTO payments (farmerId, date, amountPaid, totalOutstandingBalance)
                    SELECT ?, ?, ?, (totalOutstandingBalance - ?) FROM farmers WHERE farmerId = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("isdii", $farmerId, $date, $amountPaid, $amountPaid, $farmerId);
            $stmt->execute();

            // Update farmer's totalAmountPaid and totalOutstandingBalance
            $updateSql = "UPDATE farmers 
                          SET totalAmountPaid = totalAmountPaid + ?, 
                              totalOutstandingBalance = totalOutstandingBalance - ? 
                          WHERE farmerId = ?";
            $updateStmt = $this->conn->prepare($updateSql);
            $updateStmt->bind_param("ddi", $amountPaid, $amountPaid, $farmerId);
            $updateStmt->execute();

            // Commit transaction
            $this->conn->commit();
            echo "Payment created successfully.";
        } catch (Exception $e) {
            // Rollback transaction on error
            $this->conn->rollback();
            echo "Error creating payment: " . $e->getMessage();
        }
    }

    // Read payments with farmer details
    public function getPayments()
    {
        $sql = "SELECT p.paymentId, p.date, p.amountPaid, p.totalOutstandingBalance, 
                       f.farmerId, f.fullName
                FROM payments p
                JOIN farmers f ON p.farmerId = f.farmerId
                JOIN users u ON f.userId = u.userId";
        $result = $this->conn->query($sql);

        $payments = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $payments[] = $row;
            }
        }
        return $payments;
    }

    // Update a payment
    public function updatePayment($paymentId, $amountPaid)
    {
        // Start transaction
        $this->conn->begin_transaction();

        try {
            // Get the old payment amount and farmerId
            $sql = "SELECT amountPaid, farmerId FROM payments WHERE paymentId = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $paymentId);
            $stmt->execute();
            $result = $stmt->get_result();
            $payment = $result->fetch_assoc();

            if (!$payment) {
                throw new Exception("Payment not found.");
            }

            $oldAmountPaid = $payment['amountPaid'];
            $farmerId = $payment['farmerId'];

            // Update the payment record
            $updateSql = "UPDATE payments 
                          SET amountPaid = ?, 
                              totalOutstandingBalance = totalOutstandingBalance + (? - ?) 
                          WHERE paymentId = ?";
            $updateStmt = $this->conn->prepare($updateSql);
            $updateStmt->bind_param("ddii", $amountPaid, $oldAmountPaid, $amountPaid, $paymentId);
            $updateStmt->execute();

            // Update farmer's totalAmountPaid and totalOutstandingBalance
            $farmerUpdateSql = "UPDATE farmers 
                                SET totalAmountPaid = totalAmountPaid - ? + ?, 
                                    totalOutstandingBalance = totalOutstandingBalance + ? - ? 
                                WHERE farmerId = ?";
            $farmerUpdateStmt = $this->conn->prepare($farmerUpdateSql);
            $farmerUpdateStmt->bind_param("ddddi", $oldAmountPaid, $amountPaid, $oldAmountPaid, $amountPaid, $farmerId);
            $farmerUpdateStmt->execute();

            // Commit transaction
            $this->conn->commit();
            echo "Payment updated successfully.";
        } catch (Exception $e) {
            // Rollback transaction on error
            $this->conn->rollback();
            echo "Error updating payment: " . $e->getMessage();
        }
    }

    // Delete a payment
    public function deletePayment($paymentId)
    {
        // Start transaction
        $this->conn->begin_transaction();

        try {
            // Get the payment amount and farmerId
            $sql = "SELECT amountPaid, farmerId FROM payments WHERE paymentId = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $paymentId);
            $stmt->execute();
            $result = $stmt->get_result();
            $payment = $result->fetch_assoc();

            if (!$payment) {
                throw new Exception("Payment not found.");
            }

            $amountPaid = $payment['amountPaid'];
            $farmerId = $payment['farmerId'];

            // Delete the payment record
            $deleteSql = "DELETE FROM payments WHERE paymentId = ?";
            $deleteStmt = $this->conn->prepare($deleteSql);
            $deleteStmt->bind_param("i", $paymentId);
            $deleteStmt->execute();

            // Update farmer's totalAmountPaid and totalOutstandingBalance
            $farmerUpdateSql = "UPDATE farmers 
                                SET totalAmountPaid = totalAmountPaid - ?, 
                                    totalOutstandingBalance = totalOutstandingBalance + ? 
                                WHERE farmerId = ?";
            $farmerUpdateStmt = $this->conn->prepare($farmerUpdateSql);
            $farmerUpdateStmt->bind_param("ddi", $amountPaid, $amountPaid, $farmerId);
            $farmerUpdateStmt->execute();

            // Commit transaction
            $this->conn->commit();
            echo "Payment deleted successfully.";
        } catch (Exception $e) {
            // Rollback transaction on error
            $this->conn->rollback();
            echo "Error deleting payment: " . $e->getMessage();
        }
    }
}