<?php
include '../dbms/dbms_config.php';

// SQL to create table
// Added brand_id foreign key to link with brands table
$sql = "CREATE TABLE IF NOT EXISTS product_categories (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    brand_id INT(6) UNSIGNED NOT NULL,
    name VARCHAR(100) NOT NULL,
    image VARCHAR(255),
    description TEXT,
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'product_categories' created successfully or already exists.<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
