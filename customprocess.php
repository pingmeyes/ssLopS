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
    $domainName = $_POST["manual_domainName"];
    $projectName = mysqli_real_escape_string($conn, $_POST["projectName"]);
    $SSLStatus = mysqli_real_escape_string($conn, $_POST["manual_SSLStatus"]);
    $DaysLeftToExpire = mysqli_real_escape_string($conn, $_POST["manual_DaysLeftToExpire"]);

    // Check if the domain already exists in either table
    $sqlCheckExistence = "SELECT * FROM ssl_details INNER JOIN manual_ssl_details ON ssl_details.domainName = manual_ssl_details.domainName WHERE ssl_details.domainName = '$domainName' OR manual_ssl_details.domainName = '$domainName'";
    $resultCheckExistence = $conn->query($sqlCheckExistence);
    
    if ($resultCheckExistence->num_rows > 0) {
        // Domain already exists, set appropriate message
        $_SESSION['message'] = 'Domain already exists in the database';
        $_SESSION['message_color'] = 'red';
    } else {
        // Domain doesn't exist, proceed with insertion
        // Assuming you have functions to simulate the necessary data
        $ARecord = simulateARecord($domainName);
        $provider = simulateProvider($domainName);
        $domainProvider = simulateDomainProvider($domainName);
        $freePaidStatus = simulateFreePaidStatus($domainName);
        $dnsManager = simulateDNSManager($domainName);

        // Handle the case when DaysLeftToExpire is not available
        $daysLeftToExpireValue = empty($DaysLeftToExpire) ? 'NULL' : $DaysLeftToExpire;

        // Insert data into the database
        $sql = "INSERT INTO ssl_details (domainName, projectName, ARecord, DaysLeftToExpire, SSLStatus, Provider, DomainProvider, FreeorPaid, DNSManager)
            VALUES ('$domainName', '$projectName', '$ARecord', $daysLeftToExpireValue, '$SSLStatus', '$provider', '$domainProvider', '$freePaidStatus', '$dnsManager')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = 'Record added successfully';
            $_SESSION['message_color'] = 'green';
        } else {
            $_SESSION['message'] = 'Error: ' . $sql . '<br>' . $conn->error;
            $_SESSION['message_color'] = 'red';
        }
    }

    // Redirect back to the referring page
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

// Close connection (outside of if block to ensure it happens even if no POST)
$conn->close();
?>
