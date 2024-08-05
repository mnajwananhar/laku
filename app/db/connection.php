<?php
$servername = "project.cxw0yacw6boz.ap-southeast-1.rds.amazonaws.com";
$username = "admin";
$password = "gintoki123";
$dbname = "laundryku";
$port = 3306;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Connection successful
echo "Connected successfully";
?>
