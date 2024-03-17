<?php
// Database connection
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $domainName = mysqli_real_escape_string($conn, $_POST["manual_domainName"]);
    $projectName = mysqli_real_escape_string($conn, $_POST["projectName"]);
    $SSLStatus = mysqli_real_escape_string($conn, $_POST["manual_SSLStatus"]);
    $DaysLeftToExpire = mysqli_real_escape_string($conn, $_POST["manual_DaysLeftToExpire"]);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO manual_ssl_details (domainName, projectName, SSLStatus, DaysLeftToExpire) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $domainName, $projectName, $SSLStatus, $DaysLeftToExpire);

    // Set parameters and execute
    $stmt->execute();

    echo "Manual SSL details added successfully";

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
