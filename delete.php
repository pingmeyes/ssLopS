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
    $domainIdman = $_POST["idman"];

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

    // Delete the domain with the given ID from the database
    $sqlDelete = "DELETE FROM ssl_details WHERE id = $domainId";
    if ($conn->query($sqlDelete) === TRUE) {
        $_SESSION['message'] = 'Record from ssl_details deleted successfully';
        $_SESSION['message_color'] = 'green';
    } else {
        $_SESSION['message'] = 'Error deleting record from ssl_details: ' . $conn->error;
        $_SESSION['message_color'] = 'red';
    }

    // Delete the domain with the given ID from the manual_ssl_details table
    $sqlmanDelete = "DELETE FROM manual_ssl_details WHERE id = $domainIdman";
    if ($conn->query($sqlmanDelete) === TRUE) {
        $_SESSION['message'] .= ' Record from manual_ssl_details deleted successfully';
    } else {
        $_SESSION['message'] .= ' Error deleting record from manual_ssl_details: ' . $conn->error;
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
