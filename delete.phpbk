<?php
session_start();

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit();
}

// Check if the request method is POST and deleteBtn is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["deleteBtn"])) {
    $domainId = $_POST["id"];
     
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

    // Determine which table to delete from based on the presence of an ID
    if (isset($domainId)) {
        // Delete the domain with the given ID from the ssl_details table
        $sqlDelete = "DELETE FROM ssl_details WHERE id = $domainId";
    } 
    else {
        // No ID provided, log and redirect to the dashboard
        error_log("No ID provided for deletion");
        header("Location: index.php");
        exit();
    }

    // Execute the delete query
    if ($conn->query($sqlDelete) === TRUE) {
        $_SESSION['message'] = 'Record deleted successfully';
        $_SESSION['message_color'] = 'green';
    } else {
        $_SESSION['message'] = 'Error deleting record: ' . $conn->error;
        $_SESSION['message_color'] = 'red';
    }

    // Close the database connection
    $conn->close();
} else {
    // Invalid request, log and redirect to the dashboard
    error_log("Invalid request to delete.php");
    header("Location: index.php");
    exit();
}

// Redirect back to the dashboard
header("Location: index.php");
exit();
?>
