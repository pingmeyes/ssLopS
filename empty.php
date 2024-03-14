<?php
session_start();
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

// Delete all records from ssl_details
$sqlDeleteAll = "DELETE FROM ssl_details;";
$sqlDeleteAll = "DELETE FROM manual_ssl_details;";


if ($conn->query($sqlDeleteAll) === TRUE) {
    $_SESSION['message'] = 'All records deleted successfully';
    $_SESSION['message_color'] = 'green';
} else {
    $_SESSION['message'] = 'Error deleting records: ' . $conn->error;
    $_SESSION['message_color'] = 'red';
}

// Redirect back to the referring page
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>
