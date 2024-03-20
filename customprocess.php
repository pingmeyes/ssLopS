<?php
$config = include('/home/deploy/secrets.php');
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

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO manual_ssl_details (domainName, projectName, SSLStatus, DaysLeftToExpire) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $domainName, $projectName, $SSLStatus, $DaysLeftToExpire);

    // Set parameters and execute
    if ($stmt->execute()) {
        echo "Manual SSL details added successfully";

        // Redirect to index.php after successful insertion
        header("Location: index.php");  // Replace with your actual index.php path if necessary
        exit;  // Stop further script execution after redirecting
    } else {
        echo "Error: " . $stmt->error;
    }



    // Close statement and connection (within successful execution block)
    $stmt->close();

}

$conn->close();

?>
