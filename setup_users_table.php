<?php
require_once 'admin/include/db.php';

try {
    // Connect to database
    $db = new Database();
    $conn = $db->connect();
    
    // Drop table if exists
    $conn->exec("DROP TABLE IF EXISTS users");
    
    // Create users table
    $conn->exec("
        CREATE TABLE users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            phone VARCHAR(20) NOT NULL,
            password VARCHAR(255) NOT NULL,
            verification_token VARCHAR(64),
            is_active TINYINT(1) DEFAULT 0,
            role ENUM('customer', 'admin', 'editor') DEFAULT 'customer',
            remember_token VARCHAR(64),
            last_login DATETIME,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            INDEX idx_email (email),
            INDEX idx_role (role),
            INDEX idx_is_active (is_active)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    
    echo "Users table created successfully!";
    
} catch (PDOException $e) {
    die("Error creating users table: " . $e->getMessage());
} 