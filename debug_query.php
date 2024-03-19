<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Form</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="search-bar">Search Term:</label>
        <input type="text" id="search-bar" name="search-bar" required>
        <button type="submit">Search</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $config = include('/home/deploy/secrets.php');
        // Database connection details
        $servername = "localhost";
        $username = "root";
        $password = $config['db_password'];
        $dbname = "domaindetails";

        // Check if the search term is provided
        if (!empty($_POST['search-bar'])) {
            $searchTerm = $_POST['search-bar'];
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Modify the SQL query to include the search term and order by DaysLeftToExpire in ascending order
            $sqlFetchManualSslDetails = "SELECT * FROM manual_ssl_details WHERE domainName LIKE '%$searchTerm%' OR projectName LIKE '%$searchTerm%' ORDER BY DaysLeftToExpire ASC";

            // Print out the SQL query for debugging
            echo "<p>Debugging SQL query: $sqlFetchManualSslDetails</p>";

            // Close the database connection
            $conn->close();
        } else {
            echo "<p>Search term not provided.</p>";
        }
    }
    ?>
</body>
</html>
