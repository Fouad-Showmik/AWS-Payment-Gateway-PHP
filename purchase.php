<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$url = 'https://paymentservices.payfort.com/FortAPI/paymentApi';
$return_url = 'http://localhost/payment_process/purchase.php';

$arrData = array(
    'siganture'=>'',
    'command' => 'PURCHASE',
    'access_code' => 'QbRIlPcveY8j3Hv7CxNO',
    'merchant_identifier' => 'abKSBKKe',
    'merchant_reference' => 'Appointment-90506202232733715-j9x9UwwjQD', 
    'amount'=>1000,
    'currency' => 'USD',
    'language' => 'en',
    'expiry_date' => '10/2027',
    'card_number' => '4937280016522755', 
    'cvv'=>'592',
    'return_url' => $return_url
);


// Calculate signature
$shaString = '';
ksort($arrData);
//$SHARequestPhrase = '$2y$10$IGSCjOlk9';
//$SHAResponsePhrase = '$2y$10$IGSCjOlk9';
//$SHARequestPhrase ='PASS';
//$SHAResponsePhrase='PASS';
//$SHAType = 'sha256';

foreach ($arrData as $k => $v) {
    $shaString .= "$k=$v";
}

$shaString = $shaString .'PASS'; // Corrected the concatenation
$signature = hash('sha256', $shaString);

$arrData['signature'] = $signature;

// Initialize cURL
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:20.0) Gecko/20100101 Firefox/20.0");
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json;charset=UTF-8',
));
//curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrData));

// Execute cURL and get response
$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}

print_r($response);
curl_close($ch);
return $response;
// $result = curl_exec($ch);
// curl_close($ch);
// # Print response.
// echo "<pre>$result</pre>";