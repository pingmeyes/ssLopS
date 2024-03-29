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
// Function to fetch the latest domain details
function fetchLatestDomainDetails($domainName) {
    $daysLeftToExpire = simulateDaysLeftToExpire($domainName);
    $aRecord = simulateARecord($domainName);
    $sslStatus = checkSSLStatus($domainName, $daysLeftToExpire);

    return [
        'DaysLeftToExpire' => $daysLeftToExpire,
        'ARecord' => $aRecord,
        'SSLStatus' => $sslStatus,
    ];
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

// Simulate fetching ARecord based on the domain name
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

// Function to check SSL status
function checkSSLStatus($domainName, $daysLeftToExpire) {
    // Implement your logic to check SSL status
    // Return "Valid" or "Expired" based on the SSL status
    // This is a placeholder, replace it with your actual SSL check logic

    // Assume SSL status is determined based on DaysLeftToExpire
    // You may need to replace this with your actual SSL check
    return ($daysLeftToExpire > 0) ? "Valid" : "Expired";
}
// Fetch all existing records from the database
$sqlSelectAll = "SELECT * FROM ssl_details";
$resultSelectAll = $conn->query($sqlSelectAll);

if ($resultSelectAll->num_rows > 0) {
    while ($row = $resultSelectAll->fetch_assoc()) {
        // Get the domain name from the row
        $domainName = $row['domainName'];

        // Fetch the latest details
        $latestDetails = fetchLatestDomainDetails($domainName);

        // Update the existing record in the database
        $sqlUpdate = "UPDATE ssl_details 
                      SET DaysLeftToExpire = '{$latestDetails['DaysLeftToExpire']}',
                          ARecord = '{$latestDetails['ARecord']}',
                          SSLStatus = '{$latestDetails['SSLStatus']}'
                      WHERE domainName = '$domainName'";

        $conn->query($sqlUpdate);
    }

    $_SESSION['refresh_message'] = 'All records updated successfully';
    $_SESSION['refresh_message_color'] = 'green';
} else {
    $_SESSION['refresh_message'] = 'No records found in the database';
    $_SESSION['refresh_message_color'] = 'red';
}

// Redirect back to the referring page (dashboard.php)
header('Location: index.php');
exit();
?>
