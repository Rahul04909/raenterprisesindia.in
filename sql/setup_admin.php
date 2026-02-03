<?php
include '../dbms/dbms_config.php';

// SQL to create table
$sql = "CREATE TABLE IF NOT EXISTS admin_users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'admin_users' created successfully or already exists.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Default Admin Credentials
$admin_user = "admin";
$admin_pass = "admin123"; // Plain text password to hash
$hashed_pass = password_hash($admin_pass, PASSWORD_DEFAULT);
$admin_email = "admin@raenterprisesindia.in";

// Check if admin already exists
$check_sql = "SELECT * FROM admin_users WHERE username = '$admin_user'";
$result = $conn->query($check_sql);

if ($result->num_rows == 0) {
    $insert_sql = "INSERT INTO admin_users (username, password, email) VALUES ('$admin_user', '$hashed_pass', '$admin_email')";
    if ($conn->query($insert_sql) === TRUE) {
        echo "Default admin user created successfully.<br>";
        echo "Username: <b>$admin_user</b><br>";
        echo "Password: <b>$admin_pass</b><br>";
    } else {
        echo "Error inserting admin user: " . $conn->error . "<br>";
    }
} else {
    echo "Default admin user already exists.<br>";
}

$conn->close();
?>
