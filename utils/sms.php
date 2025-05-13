<?php
require_once "../config/db.php"; // Include database connection

function send_text_message($farmerName, $message)
{
    global $conn; // Use the database connection from the included file

    // Fetch farmer's ID and phone number from the database
    $query = "SELECT userId, phoneNumber FROM users WHERE phoneNumber = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $farmerName);
    $stmt->execute();
    $stmt->bind_result($userId, $phoneNumber);

    if ($stmt->fetch()) {
        // Ensure phone number is in the correct format (+254)
        $to = preg_replace('/^0/', '+254', $phoneNumber); // Converts 07xx to +2547xx
    } else {
        error_log("User with name {$farmerName} not found or has no phone number.");
        return null;
    }
    $stmt->close();

    // Your Africa's Talking credentials
    $username = 'nelite'; // Replace with your Africa's Talking username
    $apiKey = 'atsk_92daec573ccd1cbb5d4091f96d5ad1567928799fa24dc86ec6072be729236b80d5c0304f'; // Replace with your Africa's Talking API key

    // Validate and filter phone numbers
    $to = ltrim($to, '+'); // Remove the '+' sign if it exists

    if (!preg_match('/^2547\d{8}$|^2541\d{8}$/', $to)) {
        error_log("Invalid phone number: {$to}");
        return null;
    }

    // Prepare payload
    $postData = http_build_query([
        'username' => $username,
        'to' => $to,
        'message' => $message,
    ]);

    // Set up curl
    $ch = curl_init('https://api.africastalking.com/version1/messaging');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded',
        'Accept: application/json',
        "apiKey: $apiKey",
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute
    $response = curl_exec($ch);
    if ($response === false) {
        $errNo = curl_errno($ch);
        $errMsg = curl_error($ch);
        error_log("Curl error ({$errNo}): {$errMsg}");
        return null;
    }
    curl_close($ch);

    $result = json_decode($response, true);
    if ($result === null) {
        error_log("Invalid JSON response from Africa's Talking: {$response}");
        return null;
    }

    // Check for API-level errors
    if (!isset($result['SMSMessageData']['Recipients'][0])) {
        error_log("No recipients found in the API response.");
        return null;
    }

    return $result;
}

// Example usage
$farmerName = 'John Doe'; // Replace with the actual farmer's name
$message = 'Hello from our Keginga Farmers app!';

$result = send_text_message($farmerName, $message);

if ($result && isset($result['SMSMessageData']['Recipients'][0])) {
    $recipient = $result['SMSMessageData']['Recipients'][0];

    if ($recipient['statusCode'] === 101) {
        echo "✅ SMS queued successfully!";
    } elseif ($recipient['statusCode'] === 406) {
        echo "❌ Farmer {$recipient['number']} has opted out of SMS. Please ask them to dial *456*9# and enable marketing messages.";
    } else {
        echo "❌ SMS failed: {$recipient['status']} (code {$recipient['statusCode']}).";
    }
} else {
    echo "❌ Failed to send SMS. No valid response from the API.";
}
?>