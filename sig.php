<?php
function str_random($length = 16) {
    return bin2hex(random_bytes($length / 2));
}

$merchant_reference = str_random(30);
$redirectUrl = 'https://paymentservices.payfort.com/FortAPI/paymentApi';
$return_url = 'http://localhost/payment_process/sig.php';

$arrData = array(
    'command' => 'PURCHASE',
    'access_code' => 'QbRIlPcveY8j3Hv7CxNO',
    'merchant_identifier' => 'abKSBKKe',
    'merchant_reference' => 'Appointment-90506202232733715-kFxxzz6Rfu',
    'amount' => 1000, // Ensure amount is in cents for USD
    'currency' => 'USD',
    'language' => 'en',
    'customer_email' => 'fouad.showmik@gmail.com',
    'return_url' => $return_url,
    'expiry_date' => '1027', // Format as MMYY
    'card_number' => '4937280016522755',
    'cvv' => '592',
);

// Calculate signature
ksort($arrData);
$SHARequestPhrase = 'PASS';
$SHAType = 'sha256';
$shaString = '';

foreach ($arrData as $k => $v) {
    $shaString .= "$k=$v";
}
$shaString = $SHARequestPhrase . $shaString . $SHARequestPhrase;
$signature = hash($SHAType, $shaString);
$arrData['signature'] = $signature;

// Debug the signature string
error_log('Signature String: ' . $shaString);
error_log('Calculated Signature: ' . $signature);

// Open connection and send request to Payfort API using cURL
$ch = curl_init();
$useragent = "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:20.0) Gecko/20100101 Firefox/20.0";

curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json;charset=UTF-8'
));
curl_setopt($ch, CURLOPT_URL, $redirectUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // Allow redirects
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // Set timeout to 10 seconds
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrData));

// Execute cURL request and close connection
$response = curl_exec($ch);
$errorNumber = curl_errno($ch);
$errorMessage = curl_error($ch);
$curlInfo = curl_getinfo($ch);
curl_close($ch);

// Log the response and errors for debugging
error_log('Response: ' . print_r($response, true));
error_log('Error Number: ' . $errorNumber);
error_log('Error Message: ' . $errorMessage);
error_log('Curl Info: ' . print_r($curlInfo, true));

// Display full response for manual debugging
echo 'Response: ' . htmlspecialchars($response) . '<br>';
echo 'Error Number: ' . $errorNumber . '<br>';
echo 'Error Message: ' . $errorMessage . '<br>';
echo 'Curl Info: ' . print_r($curlInfo, true) . '<br>';

if ($response) {
    $responseData = json_decode($response, true);
    if ($responseData) {
        if (isset($responseData['status']) && $responseData['status'] == 'success') {
            echo 'Payment successful! Transaction ID: ' . $responseData['transaction_id'];
        } elseif (isset($responseData['response_message'])) {
            echo 'Payment failed: ' . $responseData['response_message'];
        } else {
            echo 'Payment failed: Unknown error.';
        }
    } else {
        echo 'Payment failed: Invalid JSON response';
    }
} else {
    echo 'Payment failed: Unable to process payment. Please try again later.';
    echo '<br />Error Number: ' . $errorNumber;
    echo '<br />Error Message: ' . $errorMessage;
}
