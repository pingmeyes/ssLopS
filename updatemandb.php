<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "domaindetails";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to increment daystoexpire for each domain
$sql = "UPDATE manual_ssl_details SET DaysLeftToExpire = DaysLeftToExpire + 1";

if ($conn->query($sql) === TRUE) {
    echo "Days to expire incremented successfully";
} else {
    echo "Error incrementing days to expire: " . $conn->error;
}

$conn->close();
?>
