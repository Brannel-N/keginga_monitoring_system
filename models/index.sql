CREATE TABLE IF NOT EXISTS `users` (
            userId INT AUTO_INCREMENT PRIMARY KEY,
            fullName VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            phoneNumber VARCHAR(20),
            createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            isActive TINYINT(1) DEFAULT 1,
            isAdmin TINYINT(1) DEFAULT 0
        );

CREATE TABLE IF NOT EXISTS `farmers` (
            farmerId INT AUTO_INCREMENT PRIMARY KEY,
            userId INT NOT NULL UNIQUE,
            location VARCHAR(255) NOT NULL,
            totalQtySold DECIMAL(10, 2) DEFAULT 0,
            totalAmountEarned DECIMAL(10, 2) DEFAULT 0,
            totalAmountPaid DECIMAL(10, 2) DEFAULT 0,
            totalOutstandingBalance DECIMAL(10, 2) DEFAULT 0,
            FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE
        );

CREATE TABLE IF NOT EXISTS `sale_records` (
            recordId INT AUTO_INCREMENT PRIMARY KEY,
            farmerId INT NOT NULL,
            date DATE NOT NULL,
            quantity DECIMAL(10, 2) NOT NULL,
            unitRate DECIMAL(10, 2) NOT NULL,
            totalAmount DECIMAL(10, 2) GENERATED ALWAYS AS (quantity * unitRate) STORED,
            FOREIGN KEY (farmerId) REFERENCES farmers(farmerId) ON DELETE CASCADE
        );

CREATE TABLE IF NOT EXISTS `rates` (
            rateId INT AUTO_INCREMENT PRIMARY KEY,
            startDate DATE NOT NULL,
            endDate DATE NOT NULL,
            unitRate DECIMAL(10, 2) NOT NULL
        );

CREATE TABLE IF NOT EXISTS payments (
            paymentId INT AUTO_INCREMENT PRIMARY KEY,
            farmerId INT NOT NULL,
            date DATE NOT NULL,
            amountPaid DECIMAL(10, 2) NOT NULL,
            totalOutstandingBalance DECIMAL(10, 2) NOT NULL,
            FOREIGN KEY (farmerId) REFERENCES farmers(farmerId) ON DELETE CASCADE
        );