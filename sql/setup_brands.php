<?php
include '../dbms/dbms_config.php';

// SQL to create table
$sql = "CREATE TABLE IF NOT EXISTS brands (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    logo VARCHAR(255),
    description TEXT,
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'brands' created successfully or already exists.<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
