<?php
session_start(); // Start the session

// Include configuration (assuming secrets.php is outside the document root for security)
$config = include('/home/deploy/secrets.php'); // Adjust path if necessary

// Database connection
$servername = "localhost";
$username = "root";
$password = $config['db_password'];
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

    // Check if domain already exists in either table
    $sqlCheckExistence = "SELECT * FROM ssl_details WHERE domainName = '$domainName'
    UNION ALL
    SELECT * FROM manual_ssl_details WHERE domainName = '$domainName'";
    $resultCheckExistence = $conn->query($sqlCheckExistence);

    if ($resultCheckExistence->num_rows > 0) {
        // Domain already exists, set appropriate message
        $_SESSION['message'] = 'Domain already exists in the database';
    } else {
        // Domain doesn't exist, proceed with insertion
        $sqlInsert = "INSERT INTO manual_ssl_details (domainName, projectName, SSLStatus, DaysLeftToExpire) VALUES ('$domainName', '$projectName', '$SSLStatus', $DaysLeftToExpire)";
        
        if ($conn->query($sqlInsert) === TRUE) {
            $_SESSION['message'] = "Domian added successfully";
        } else {
            $_SESSION['message'] = "Error: " . $conn->error;
        }
    }

    // Redirect to index.php after successful insertion or if domain already exists
    header("Location: index.php");
    exit;
}

// Close connection (outside of if block to ensure it happens even if no POST)
$conn->close();
?>
