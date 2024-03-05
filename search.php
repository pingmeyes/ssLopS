<?php
// Start the session
session_start();

// Database connection details
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = "root"; // Replace with your database password
$dbname = "domaindetails"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve search term from the POST request
$searchTerm = $_POST['search-bar'];

// Prepare SQL query to search in both domainName and projectName columns
$sql = "SELECT * FROM ssl_details WHERE domainName LIKE '%$searchTerm%' OR projectName LIKE '%$searchTerm%'";

// Execute the query
$result = $conn->query($sql);

// Check if any results were found
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "Domain Name: " . $row["domainName"]. " - Project Name: " . $row["projectName"]. "<br>";
    }
} else {
    echo "0 results";
}

// Close the database connection
$conn->close();
?>