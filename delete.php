<?php
echo "ID: " . $_POST["id"]; // Add this line for debugging
session_start();

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit();
}

// Check if the request method is GET and the domain ID is set
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $domainId = $_GET["id"];

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
        $_SESSION['message'] = 'Record deleted successfully';
        $_SESSION['message_color'] = 'green';
    } else {
        $_SESSION['message'] = 'Error deleting record: ' . $conn->error;
        $_SESSION['message_color'] = 'red';
    }

    // Close the database connection
    $conn->close();

    // Redirect back to the dashboard
    header("Location: index.php");
    exit();
} else {
    // Invalid request, redirect to the dashboard
    header("Location: index.php");
    exit();
}
?>
