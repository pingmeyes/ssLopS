<?php
session_start();

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SSL Manager</title>
    <style>
        body {
            font-family: "Lato", sans-serif;
            margin: 0;
        }

        .sidenav {
            height: 100%;
            width: 160px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #111;
            overflow-x: hidden;
            padding-top: 20px;
        }

        .sidenav a {
            padding: 6px 8px 6px 16px;
            text-decoration: none;
            font-size: 20px;
            color: #818181;
            display: block;
        }

        .sidenav a:hover {
            color: #f1f1f1;
        }

        .main {
            margin-left: 160px; /* Same as the width of the sidenav */
            font-size: 24px; /* Increased text to enable scrolling */
            padding: 20px 10px;
        }

        @media screen and (max-height: 450px) {
            .sidenav {
                padding-top: 15px;
            }

            .sidenav a {
                font-size: 18px;
            }
        }

        form.left-form {
            width: 95%;
            max-width: 400px;
            margin: 25px 0;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .top-right-section {
           position: fixed;
           top: 20px; /* Adjust top position if needed */
           right: 20px; /* Adjust right position if needed */
           color: #fff;
           font-size: 18px;
           border: 1px solid #000; /* Add border with black color */
           padding: 10px; /* Add padding to the box */
           display: inline-block; /* Display as block to prevent filling to the left */
           white-space: nowrap; /* Prevent wrapping to the next line */
        }

         /* To make the list vertical */
        .expiry-box-list {
           display: block;
           margin-top: 10px; /* Adjust margin as needed */
        }
        .dashboard-section {
            padding: 20px;
            margin-top: 50px; /* Adjusted margin-top to avoid overlap with fixed top-right section */
        }

        .dashboard-section h2 {
            margin-bottom: 20px;
        }

        .dashboard-actions {
            margin-bottom: 20px;
        }

        .search-bar {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .domain-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .domain-table th,
        .domain-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .domain-table th {
            background-color: #f2f2f2;
        }

        .renew-button {
            padding: 8px 16px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .renew-button:hover {
            background-color: #45a049;
        }

        /* New styles for highlighting expiration days */
        .expiry-box {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }

        .expiry-box-red {
            background-color: #ff3333; /* Red for below 10 days */
            color: #fff;
        }

        .expiry-box-orange {
            background-color: #ff9900; /* Orange for below 20 days */
            color: #fff;
        }

        .expiry-box-dark-yellow {
            background-color: #cc9900; /* Dark Yellow for below 30 days */
            color: #fff;
        }
    </style>
</head>
<body>

<div class="sidenav">
    <a href="#sslmanager">SSL Manager</a>
    <a href="ra.html">Resource Analyzer</a>
    <a href="docs.html">Documents</a>
</div>

<div class="main">
    <h2>SSL Manager</h2>

    <form class="left-form" action="/process.php" method="post">
        <h2>Add Domain</h2>
        <label for="domainName">Domain name:</label>
        <input type="text" id="domainName" name="domainName" required>

        <label for="projectName">Project name:</label>
        <select id="projectName" name="projectName" required>
            <option value="E-VS">E-VS</option>
            <option value="RealtyNinja">Realty Ninja</option>
            <option value="Courier NetWork">Courier NetWork</option>
            <option value="Quavered">Quavered</option>
            <option value="Phobs">Phobs</option>
            <option value="32auctions">32auctions</option>
            <option value="Logoup">Logoup</option>
            <option value="NikeSB">NikeSB</option>
            <option value="waitrainer">waitrainer</option>
            <option value="Acumed">Acumed</option>
            <option value="Next reason">Next reason</option>
        </select>

        <button type="submit">Submit</button>
    </form>

    <div><a href="empty.php" onclick="return confirm('Are you sure you want to delete all records?');">Delete All Records</a></div>

    <div class="dashboard-section">
        <h2>Dashboard</h2>
        <div class="dashboard-actions">
            <div class="search-bar-container">
                <input class="search-bar" placeholder="Search..." type="text">
                <form action="refresh.php" method="post">
                    <button type="submit">Refresh</button>
                </form>
            </div>
        </div>
  
        <table class="domain-table">
            <thead>
            <tr>
               <th>Domain Name</th>
               <th>Project Name</th>
               <th>SSL Status</th>
               <th>Days Left to Expire</th>
               <th>A Record</th>
               <th>SSL Provider</th>
               <th>Free/Paid</th>
               <th>DNS Manager</th>
               <th>Domain provider</th>
               <th>Actions</th> <!-- Moved "Actions" to the end -->
            </tr>
            </thead>
            <tbody>
            <?php
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
            // Display success or error message
           if (isset($_SESSION['message'])) {
               $message = $_SESSION['message'];
               $message_color = isset($_SESSION['message_color']) ? $_SESSION['message_color'] : 'black';
    
               echo '<p style="color: ' . $message_color . ';">' . $message . '</p>';
    
              // Clear the session message
              unset($_SESSION['message']);
              unset($_SESSION['message_color']);
            }
            // Fetch all records from the database for the dashboard
            $sqlFetchAll = "SELECT * FROM ssl_details";
            $resultFetchAll = $conn->query($sqlFetchAll);
    
            // Loop through the rows in the result set
            while ($row = $resultFetchAll->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['domainName'] . '</td>';
                echo '<td>' . $row['projectName'] . '</td>';
                echo '<td>' . $row['SSLStatus'] . '</td>';
                echo '<td>' . $row['DaysLeftToExpire'] . ' days</td>';
                echo '<td>' . $row['ARecord'] . '</td>';
                echo '<td>' . $row['Provider'] . '</td>';
                echo '<td>' . $row['FreeorPaid'] . '</td>';
                echo '<td>' . $row['DNSManager'] . '</td>';
                echo '<td>' . $row['DomainProvider'] . '</td>';
                echo '<td>';
                // Styled delete button
                echo '<form action="delete.php" method="post" style="display:inline-block; margin-right: 5px;">';
                echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
                echo '<button type="submit" name="deleteBtn" style="background-color: #f44336; color: white; border: none; padding: 8px 12px; cursor: pointer; border-radius: 4px;">Delete</button>';
                echo '</form>';
                // Add more action buttons if needed
                echo '</td>';
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    
        <div class="top-right-section">
            <h2>Expiring Domains</h2>
            <!-- Display expiring domains within the main content -->
<?php
// Fetch domains with expiry days less than 30
$sqlFetchExpiring = "SELECT * FROM ssl_details WHERE DaysLeftToExpire < 30";
$resultFetchExpiring = $conn->query($sqlFetchExpiring);

while ($rowExpiring = $resultFetchExpiring->fetch_assoc()) {
    $expiryDays = $rowExpiring['DaysLeftToExpire'];
    $expiryClass = '';
    if ($expiryDays < 10) {
        $expiryClass = 'expiry-box-red';
    } elseif ($expiryDays < 20) {
        $expiryClass = 'expiry-box-orange';
    } elseif ($expiryDays < 30) {
        $expiryClass = 'expiry-box-dark-yellow';
    }

    echo '<div class="expiry-box ' . $expiryClass . '">';
    echo $rowExpiring['domainName'] . ' - Expires in ' . $expiryDays . ' days';
    echo '</div>';
}
?>

        </div>    

      </div>
</div>

</body>
</html>
