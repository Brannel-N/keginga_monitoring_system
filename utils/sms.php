<?php
/**
 * Send an SMS via Africa's Talking
 *
 * @param string|array $to      A single phone number or an array of phone numbers in international format (e.g. "+2547XXXXXXXX")
 * @param string       $message The text message to send
 * @return array|null           Decoded API response on success, or null on failure
 */
function send_text_message($to, $message)
{
    // Your Africa's Talking credentials
    $username = '';    // e.g. 'sandbox' for testing
    $apiKey = '';
    $from = '';      // optional (alphanumeric approved sender ID)

    // Prepare payload
    if (is_array($to)) {
        $to = implode(',', $to);
    }
    $postData = http_build_query([
        'username' => $username,
        'to' => $to,
        'message' => $message,
        // 'from'  => $from,  // uncomment if you have a registered sender ID
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
    $errNo = curl_errno($ch);
    $errMsg = curl_error($ch);
    curl_close($ch);

    if ($errNo) {
        error_log("Africa's Talking SMS curl error ({$errNo}): {$errMsg}");
        return null;
    }

    $result = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("Invalid JSON response from Africa's Talking: {$response}");
        return null;
    }

    // Check for API-level errors
    if (
        isset($result['SMSMessageData']['Recipients'][0]['statusCode']) &&
        $result['SMSMessageData']['Recipients'][0]['statusCode'] !== 101
    ) {
        // 101 = queued/sent
        $status = $result['SMSMessageData']['Recipients'][0];
        error_log("SMS failed for {$status['number']}: {$status['status']} ({$status['statusCode']})");
    }

    return $result;
}

// $to = ['+254740924507'];
// $message = 'Hello from our Keginga Farmers app!';

// $result = send_text_message($to, $message);
// $recipient = $result['SMSMessageData']['Recipients'][0];

// if ($recipient['statusCode'] === 101) {
//     echo "✅ SMS queued successfully!";
// } elseif ($recipient['statusCode'] === 406) {
//     echo "❌ Farmer {$recipient['number']} has opted out of SMS. Please ask them to dial *456*9# and enable marketing messages.";
// } else {
//     echo "❌ SMS failed: {$recipient['status']} (code {$recipient['statusCode']}).";
// }

