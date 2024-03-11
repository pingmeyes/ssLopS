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
            padding: 0 0 20px 10px;
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
            width: 100%;
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
        

         /* To make the list vertical */
        .expiry-box-list {
           display: block;
           margin-top: 10px; /* Adjust margin as needed */
        }
        .dashboard-section {
    padding: 20px;
    margin-top: 50px; /* Adjusted margin-top to avoid overlap with fixed top-right section */
    margin-right: 220px; /* Added margin to the right to create space for fixed top-right section */
    background-color: #fff; /* Added a background color to hide content behind the fixed section */
    position: relative; /* Ensure relative positioning for absolute child elements */
    z-index: 0; /* Lower z-index to keep the content behind the fixed section */
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
            
        }

        .expiry-box-red {
             /* Red for below 10 days */
            color: #ff3737;
            padding:10px 0 10px 0;
            
            
        }

        .expiry-box-orange {
             /* Orange for below 20 days */
            color: #ff8306;
            padding:10px 0 10px 0;
            
            
        }

        .expiry-box-dark-yellow {
             /* Dark Yellow for below 30 days */
            color: #2ac8e4;
            padding:10px 0 10px 0;
            
            
        }
        .top-right-sec h4{
          color: #000;
          text-align:center;
          
        }
        .top-right-sec {
          padding: 15px 20px 15px 20px;
          background:#4caf5033;
          border-radius:30px 0 30px 30px; 
        }
        .ssl-exp{
          display:flex;
          justify-content:space-between;
          padding: 0 0 0 20px;
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
  <div class="ssl-exp">

  <div class="ffsfj">
    

    <form class="left-form" action="/process.php" method="post">
        <h1>Add Domain</h1>
        <label for="domainName">Domain name:</label>
        <input style="width:400px" type="text" id="domainName" name="domainName" required>

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
    
<form action="manualprocess.php" method="post">
     <h1>Monitoring MFn1</h1>
    <label for="manual_domainName">Domain Name:</label>
    <input type="text" id="manual_domainName" name="manual_domainName" required>
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
    <label for="manual_SSLStatus">SSL Status:</label>
    <input type="text" id="manual_SSLStatus" name="manual_SSLStatus" required>
    <label for="manual_DaysLeftToExpire">Days Left to Expire:</label>
    <input type="number" id="manual_DaysLeftToExpire" name="manual_DaysLeftToExpire" required>
    <input type="submit" value="Add to Dashboard">
</form>

      
    <div><a href="empty.php" onclick="return confirm('Are you sure you want to delete all records?');">Delete All Records</a></div>
    </div>
    <div class="top-right-sec">
        <h4>Expiring Domains</h4>
        <!-- Display expiring domains within the main content -->
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
    <div class="dashboard-section">
        <h2>Dashboard</h2>
        <div class="dashboard-actions">
          <form action="index.php" method="post">
            <input class="search-bar" placeholder="Search..." type="text" name="search-bar">
            <button type="submit">Search</button>
          </form>
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

// Database connection details
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

// Check if the search form is submitted
if (isset($_POST['search-bar']) && !empty($_POST['search-bar'])) {
    $searchTerm = $_POST['search-bar'];
    // Modify the SQL query to include the search term
    $sqlFetchAll = "SELECT * FROM ssl_details WHERE domainName LIKE '%$searchTerm%' OR projectName LIKE '%$searchTerm%'";
    $sqlFetchManualSslDetails = "SELECT * FROM manual_ssl_details WHERE domainName LIKE '%$searchTerm%' OR projectName LIKE '%$searchTerm%'";
} else {
    // Default query without filtering
    $sqlFetchAll = "SELECT * FROM ssl_details";
    $sqlFetchManualSslDetails = "SELECT * FROM manual_ssl_details";
}

// Execute the query
$resultFetchAll = $conn->query($sqlFetchAll);
$resultManualSslDetails = $conn->query($sqlFetchManualSslDetails);

// Check if any results were found
if ($resultFetchAll->num_rows > 0) {
    // Display success or error message
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $message_color = isset($_SESSION['message_color']) ? $_SESSION['message_color'] : 'black';

        echo '<p style="color: ' . $message_color . ';">' . $message . '</p>';

        // Clear the session message
        unset($_SESSION['message']);
        unset($_SESSION['message_color']);
    }

    // Loop through the rows in the result set
    while ($row = $resultFetchAll->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['domainName']) . '</td>';
        echo '<td>' . htmlspecialchars($row['projectName']) . '</td>';
        echo '<td>' . htmlspecialchars($row['SSLStatus']) . '</td>';
        echo '<td>' . htmlspecialchars($row['DaysLeftToExpire']) . ' days</td>';
        echo '<td>' . htmlspecialchars($row['ARecord']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Provider']) . '</td>';
        echo '<td>' . htmlspecialchars($row['FreeorPaid']) . '</td>';
        echo '<td>' . htmlspecialchars($row['DNSManager']) . '</td>';
        echo '<td>' . htmlspecialchars($row['DomainProvider']) . '</td>';
        echo '<td>';
        // Styled delete button
        echo '<form action="delete.php" method="post" style="display:inline-block; margin-right: 5px;">';
        echo '<input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">';
        echo '<button type="submit" name="deleteBtn" style="background-color: #f44336; color: white; border: none; padding: 8px 12px; cursor: pointer; border-radius: 4px;">Delete</button>';
        echo '</form>';
        // Add more action buttons if needed
        echo '</td>';
        echo '</tr>';
    }
    while ($row = $resultManualSslDetails->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['domainName']) . '</td>';
        echo '<td>' . htmlspecialchars($row['projectName']) . '</td>';
        echo '<td>' . htmlspecialchars($row['SSLStatus']) . '</td>';
        echo '<td>' . htmlspecialchars($row['DaysLeftToExpire']) . ' days</td>';
        echo '<td>';
        // Styled delete button
        echo '<form action="delete.php" method="post" style="display:inline-block; margin-right: 5px;">';
        echo '<input type="hidden" name="idman" value="' . htmlspecialchars($row['id']) . '">';
        echo '<button type="submit" name="deleteBtn" style="background-color: #f44336; color: white; border: none; padding: 8px 12px; cursor: pointer; border-radius: 4px;">Delete</button>';
        echo '</form>';

        // Add more action buttons if needed
        echo '</td>';
        echo '</tr>';
    }
} else {
    echo "0 results";
}

// Close the database connection
?>

            </tbody>
        </table>
            

      </div>
</div>

</body>
</html>
