<?php
include '../dbms/dbms_config.php';

// SQL to create table
$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT(6) UNSIGNED,
    brand_id INT(6) UNSIGNED,
    name VARCHAR(255) NOT NULL,
    image VARCHAR(255),
    description LONGTEXT,
    is_price_enabled BOOLEAN DEFAULT FALSE,
    mrp DECIMAL(10, 2),
    sale_price DECIMAL(10, 2),
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords TEXT,
    schema_markup LONGTEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES product_categories(id) ON DELETE SET NULL,
    FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE SET NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'products' created successfully or already exists.<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
