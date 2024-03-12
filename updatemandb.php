<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "pass";
$dbname = "domaindetails";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to increment daystoexpire for each domain
$sql = "UPDATE your_table_name SET daystoexpire = daystoexpire + 1";

if ($conn->query($sql) === TRUE) {
    echo "Days to expire incremented successfully";
} else {
    echo "Error incrementing days to expire: " . $conn->error;
}

$conn->close();
?>
