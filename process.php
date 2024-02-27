<?php
include 'functions.php';
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
function simulateProvider($domainName) {
    // Simulate fetching the provider based on the domain name
    // Replace this with your actual logic for obtaining the provider

    $command = "echo | openssl s_client -servername $domainName -connect $domainName:443 2>/dev/null | openssl x509 -noout -issuer | grep -oP '(?<=O = )[^,]+'";

    // Execute the command and get the output
    $provider = shell_exec($command);

    // Check if the provider is obtained successfully
    if ($provider !== null) {
        // Trim any whitespace and return the provider name
        $provider = trim($provider);

        // Replace "Let's Encrypt" with "Certbot"
        if ($provider === "Let's Encrypt") {
            $provider = "Certbot";
        }

        return $provider;
    } else {
        // If the command fails or provider is not found, return a default value or handle accordingly
        return "UnknownProvider";
    }
}

function simulateDomainProvider($domainName) {
    // Simulate fetching the domain provider based on the domain name
    // Replace this with your actual logic for obtaining the domain provider

    // Example: Hardcoded value for demonstration purposes
    return "ExampleDomainProvider";
}
function simulateFreePaidStatus($domainName) {
    // Simulate fetching SSL certificate details based on the domain name
    $sslDetails = shell_exec("openssl s_client -connect $domainName:443 -showcerts </dev/null 2>/dev/null | openssl x509 -noout -dates");

    // Extract the notBefore and notAfter dates from the certificate details
    preg_match('/notBefore=(.*?)\n/', $sslDetails, $notBeforeMatches);
    preg_match('/notAfter=(.*?)\n/', $sslDetails, $notAfterMatches);

    if (isset($notBeforeMatches[1]) && isset($notAfterMatches[1])) {
        // Convert dates to timestamps
        $notBeforeTimestamp = strtotime($notBeforeMatches[1]);
        $notAfterTimestamp = strtotime($notAfterMatches[1]);

        // Calculate the total number of days the SSL certificate is valid
        $totalDays = ($notAfterTimestamp - $notBeforeTimestamp) / (60 * 60 * 24);

        // Determine if the SSL is free or paid based on the total number of days
        return ($totalDays > 100) ? 'Paid' : 'Free';
    } else {
        // Return 'Unknown' if unable to extract dates
        return 'Unknown';
    }
}
function simulateDNSManager($domainName) {
    // Replace this with your actual logic for obtaining the DNS Manager
    $command = "nslookup -type=ns $domainName";

    // Execute the command and get the output
    $dnsManagerOutput = shell_exec($command);

    // Check if DNS Manager is obtained successfully
    if ($dnsManagerOutput !== null) {
        // Extract relevant information from the output (adjust as needed)
        preg_match('/nameserver = (.*)/', $dnsManagerOutput, $matches);
        $dnsManager = isset($matches[1]) ? trim($matches[1]) : null;

        // Return the DNS Manager name
        return !empty($dnsManager) ? $dnsManager : "UnknownDNSManager";
    } else {
        // If the command fails or DNS Manager is not found, return a default value or handle accordingly
        return "UnknownDNSManager";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $domainName = $_POST["domainName"];
    $projectName = mysqli_real_escape_string($conn, $_POST["projectName"]);

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


        $provider = simulateProvider($domainName);
        $domainProvider = simulateDomainProvider($domainName);
        $freePaidStatus = simulateFreePaidStatus($domainName);
        $dnsManager = simulateDNSManager($domainName);


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
