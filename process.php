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

// Simulate fetching ARecord based on the domain name
function simulateDaysLeftToExpire($domainName) {
    $context = stream_context_create([
        "ssl" => [
            "capture_peer_cert" => true
        ]
    ]);

    $socket = stream_socket_client("ssl://$domainName:443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context);

    if (!$socket) {
        return false;
    }

    $params = stream_context_get_params($socket);
    $certificate = openssl_x509_parse($params['options']['ssl']['peer_certificate']);

    if (!$certificate || empty($certificate['validTo_time_t'])) {
        return false;
    }

    fclose($socket);

    $expiryTimestamp = $certificate['validTo_time_t'];
    $currentTimestamp = time();
    $secondsRemaining = $expiryTimestamp - $currentTimestamp;
    $daysRemaining = floor($secondsRemaining / (60 * 60 * 24));

    return $daysRemaining;
}


function simulateARecord($domainName) {
    // Simulate fetching ARecord based on the domain name
    // Replace this with your actual logic for obtaining ARecord

    // Use the host command to get the ARecord
    $hostCommand = "host $domainName";
    $result = shell_exec($hostCommand);

    // Extract the ARecord from the result
    preg_match('/has address (.*)/', $result, $matches);

    // Return the ARecord
    return isset($matches[1]) ? trim($matches[1]) : null;
}
function checkSSLStatus($domainName, $DaysLeftToExpire) {
    // Implement your logic to check SSL status
    // Return "Valid" or "Expired" based on the SSL status
    // This is a placeholder, replace it with your actual SSL check logic

    // Assume SSL status is determined based on DaysLeftToExpire
    // You may need to replace this with your actual SSL check
    return ($DaysLeftToExpire > 0) ? "Valid" : "Expired";
}
function updateProvider($conn, $domainName) {
    // Execute the openssl command and get the issuer
    $issuer = shell_exec("echo | openssl s_client -connect $domainName:443 2>/dev/null | openssl x509 -noout -issuer");

    // Extract the provider name (assuming it's always after "O = ")
    $provider = '';
    if (preg_match('/O = (.+?)(,|$)/', $issuer, $matches)) {
        $provider = trim($matches[1]);
    }

    // Update the database with the provider information
    $sqlUpdateProvider = "UPDATE ssl_details SET Provider = '$provider' WHERE domainName = '$domainName'";
    $conn->query($sqlUpdateProvider);
}

function updateDomainProvider($conn, $domainName) {
    // Implement your logic to fetch and update domain provider information
    // Example: $domainProvider = getDomainProvider($domainName);
    $domainProvider = 'SampleDomainProvider';

    // Update the database with the domain provider information
    $sqlUpdateDomainProvider = "UPDATE ssl_details SET DomainProvider = '$domainProvider' WHERE domainName = '$domainName'";
    $conn->query($sqlUpdateDomainProvider);
}

function updateFreePaidStatus($conn, $domainName) {
    // Implement your logic to determine free/paid status
    // Example: $freePaidStatus = determineFreePaidStatus($domainName);
    $freePaidStatus = 'Paid';

    // Update the database with the free/paid status
    $sqlUpdateFreePaid = "UPDATE ssl_details SET FreePaidStatus = '$freePaidStatus' WHERE domainName = '$domainName'";
    $conn->query($sqlUpdateFreePaid);
}

function updateDNSManager($conn, $domainName) {
    // Implement your logic to fetch and update DNS manager information
    // Example: $dnsManager = getDNSManager($domainName);
    $dnsManager = 'SampleDNSManager';

    // Update the database with the DNS manager information
    $sqlUpdateDNSManager = "UPDATE ssl_details SET DNSManager = '$dnsManager' WHERE domainName = '$domainName'";
    $conn->query($sqlUpdateDNSManager);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $domainName = $_POST["domainName"];
    $projectName = $_POST["projectName"];

    // Check if the domain already exists
    $sqlCheckExistence = "SELECT * FROM ssl_details WHERE domainName = '$domainName'";
    $resultCheckExistence = $conn->query($sqlCheckExistence);
    
    if ($resultCheckExistence->num_rows > 0) {
        // Domain already exists, set appropriate message
        $_SESSION['message'] = 'Domain already exists in the database';
        $_SESSION['message_color'] = 'red';
    } else {
        // Call the checkSSLStatus function
        $DaysLeftToExpire = simulateDaysLeftToExpire($domainName); // Implement this function similarly
        $SSLStatus = checkSSLStatus($domainName, $DaysLeftToExpire);
        
        // Handle the case when DaysLeftToExpire is not available
        $daysLeftToExpireValue = empty($DaysLeftToExpire) ? 'NULL' : $DaysLeftToExpire;

        // Get SSL details dynamically
        $ARecord = simulateARecord($domainName);


        updateProvider($conn, $domainName);
        updateDomainProvider($conn, $domainName);
        updateFreePaidStatus($conn, $domainName);
        updateDNSManager($conn, $domainName);


        // Insert data into the database
        $sql = "INSERT INTO ssl_details (domainName, projectName, ARecord, DaysLeftToExpire, SSLStatus, Provider, DomainProvider, FreeorPaid, DNSManager)
            VALUES ('$domainName', '$projectName', '$ARecord', $daysLeftToExpireValue, '$SSLStatus', '$provider', '$domainProvider', '$freePaidStatus', '$dnsManager')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = 'Record added successfully';
            $_SESSION['message_color'] = 'green';
        } else {
            $_SESSION['message'] = 'Error: ' . $sql . '<br>' . $conn->error;
            $_SESSION['message_color'] = 'red';
        }
    }

    // Redirect back to the referring page
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
?>
