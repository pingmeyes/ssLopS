<?php
// Include configuration (assuming secrets.php is outside the document root for security)
$config = $config = include('/home/deploy/secrets.php');  // Adjust path if necessary

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
    $DaysLeftToExpire = mysqli_real_escape_string($conn, $_POST["manual_SDLaysLeftToExpire"]);

    // Check if domain already exists in either table
    $sqlCheckExistence = "SELECT * FROM ssl_details INNER JOIN manual_ssl_details ON ssl_details.domainName = manual_ssl_details.domainName WHERE ssl_details.domainName = '$domainName' OR manual_ssl_details.domainName = '$domainName'";
    $resultCheckExistence = $conn->query($sqlCheckExistence);

    if ($resultCheckExistence->num_rows > 0) {
        // Domain already exists, set a message variable
        $message = 'Domain already exists in the database';
    } else {
        // Domain doesn't exist, proceed with insertion
        $stmt = $conn->prepare("INSERT INTO manual_ssl_details (domainName, projectName, SSLStatus, DaysLeftToExpire) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $domainName, $projectName, $SSLStatus, $DaysLeftToExpire);

        if ($stmt->execute()) {
            $message = "Manual SSL details added successfully";

            // Redirect to avoid form resubmission on refresh (optional)
            // header("Location: index.php");  // Replace with your actual index.php path if necessary
            // exit;  // Stop further script execution after redirecting
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Close connection
$conn->close();

?>