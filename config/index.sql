CREATE TABLE USERS(
    user_id INT AUTO_INCREMENT,
    Full_name VARCHAR(150) NOT NULL,
    email VARCHAR(200) UNIQUE,
    Contact VARCHAR(50),
    Location VARCHAR(100),
    role ENUM('USER','ADMIN') DEFAULT 'USER',
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY(user_id)
    
);

ALTER TABLE users 
MODIFY COLUMN password VARCHAR(255) NULL;
