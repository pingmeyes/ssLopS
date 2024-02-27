<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
  font-family: "Lato", sans-serif;
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
  font-size: 25px;
  color: #818181;
  display: block;
}

.sidenav a:hover {
  color: #f1f1f1;
}

.main {
  margin-left: 20px; /* Same as the width of the sidenav */
  font-size: 28px; /* Increased text to enable scrolling */
  padding: 0px 10px;
}

@media screen and (max-height: 450px) {
  .sidenav {padding-top: 15px;}
  .sidenav a {font-size: 18px;}
}
body {
      font-family: Arial, sans-serif;
    }
    form {
      max-width: 400px;
      margin: 20px auto;
    }
    label {
      display: block;
      margin-bottom: 8px;
    }
    input, select {
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
    body {
      margin: 0;
      font-family: 'Arial', sans-serif;
    }

    .app-container {
      display: flex;
    }

    .sidebar {
      /* ... (your existing sidebar styles) ... */
    }

    .dashboard-section {
      padding: 20px;
      flex-grow: 1;
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
</style>
</head>
<body>

<div class="sidenav">
  <a href="#sslmanager">SSL Manager</a>
  <a href="#RA">Resource Analyzer</a>
  <a href="#cm">Cost monitor</a>
  <a href="#docs">Documents</a>
</div>

<div class="main">
  <h2>SSL Manager</h2>
  <form action="/process.php" method="post">
    <h2>Add Domain</h2> 
    <label for="domainName">Domain name:</label>
    <input type="text" id="domainName" name="domainName" required>

    <label for="projectName">Project name:</label>
    <select id="projectName" name="projectName" required>
      <option value="E-VS">E-VS</option>
      <option value="Realty Ninja">Realty Ninja</option>
      <option value="Courier NetWork">Courier NetWork</option>
      <option value="Quavered">Quavered</option>
      <option value="Phobs">Phobs</option>
      <option value="PCI">PCI</option>
      <option value="test">test</option>
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
        </tr>
      </thead>
      <tbody>
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
            echo '</tr>';
        }
        ?>
        <!-- Add more rows as needed -->
    </tbody>
    </table>
  </div>
</div>
   
</body>
</html>
