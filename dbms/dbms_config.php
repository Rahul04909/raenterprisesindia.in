<?php
// Database credentials
$servername = "localhost";
$username = "jghfrodu_ra_enterprises";
$password = "Rd14072003@./";
$dbname = "jghfrodu_ra_enterprises"; // Defining a specific DB name for the project

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // If database does not exist, try connecting without DB name and creating it
    if ($conn->connect_error == "Unknown database '$dbname'") {
        $conn = new mysqli($servername, $username, $password);
        if ($conn->connect_error) {
             die("Connection failed: " . $conn->connect_error);
        }
        $sql = "CREATE DATABASE $dbname";
        if ($conn->query($sql) === TRUE) {
            // Reconnect with database
            $conn = new mysqli($servername, $username, $password, $dbname);
        } else {
             die("Error creating database: " . $conn->error);
        }
    } else {
        die("Connection failed: " . $conn->connect_error);
    }
}
?>
