<?php
include '../dbms/dbms_config.php';

// SQL to create table
$sql = "CREATE TABLE IF NOT EXISTS product_reviews (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT(6) UNSIGNED,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    rating INT(1) NOT NULL,
    review TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'product_reviews' created successfully or already exists.<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
