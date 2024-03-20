<?php
// Include configuration (assuming secrets.php is outside the document root for security)
$config = include realpath(__DIR__ . '/../secrets.php');  // Adjust path if necessary

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
    $sqlCheckExistence = "SELECT * FROM ssl_details INNER JOIN manual_ssl_details ON ssl_details.domainName = manual_ssl_details.domainName WHERE ssl_details.domainName = '$domainName' OR manual_ssl_details.domainName = '$domainName'";
    $resultCheckExistence = $conn->query($sqlCheckExistence);

    if ($resultCheckExistence->num_rows > 0) {
        // Domain already exists, set appropriate message (assuming you have a way to display messages to the user)
        echo 'Domain already exists in the database';
    } else {
        // Domain doesn't exist, proceed with insertion
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

        // Close statement (within successful execution block)
        $stmt->close();
    }
}

// Close connection (outside of if block to ensure it happens even if no POST)
$conn->close();

?>
