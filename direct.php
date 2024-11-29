<?php
function str_random($length = 16) {
    return bin2hex(random_bytes($length / 2));
}

$merchant_reference = str_random(30);
$merchant_reference = 'Appointment-90506202232733715-5xwMtZhxyD';
$redirectUrl = 'https://paymentservices.payfort.com/FortAPI/paymentApi';
$return_url = 'https://wafid.com/appointment/J1Z64WrGQkwnGq8/pay/';
//https://checkout.payfort.com/FortAPI/paymentPage
$requestParams = array(
    'command' => 'PURCHASE',
    'access_code' => 'QbRIlPcveY8j3Hv7CxNO',
    'merchant_identifier' => 'abKSBKKe',
    'merchant_reference' => 'Appointment-90506202232733715-5xwMtZhxy',
    'amount' =>  1000, // Ensure amount is in minor units (fils)
    'currency' => 'USD',
    'language' => 'en',
    'customer_email' => "mutasim.fouad.showmik@gmail.com",
    'return_url' => $return_url,
    'card_number' => '4088325005275225', // Example card number (VISA)
    'expiry_month' => '02',
    'expiry_year' => '2027',
    'cvv' => '135'
);

// Calculate signature
$shaString = '';
ksort($requestParams);
$SHARequestPhrase = '$2y$10$IGSCjOlk9';
$SHAResponsePhrase = '$2y$10$Ll6DBCeeH';
//$SHARequestPhrase ='PASS';
//$SHAResponsePhrase='PASS';
$SHAType = 'sha256';

foreach ($requestParams as $k => $v) {
    $shaString .= "$k=$v";
}

$shaString = $SHARequestPhrase . $shaString .$SHAResponsePhrase; // Corrected the concatenation
$signature = hash($SHAType, $shaString);

$requestParams['signature'] = hash($SHAType, $shaString);

// Initialize cURL
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:20.0) Gecko/20100101 Firefox/20.0");
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json;charset=UTF-8',
));
curl_setopt($ch, CURLOPT_URL, $redirectUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestParams));

// Execute cURL and get response
$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}

print_r($response);
curl_close($ch);
$requestParams=json_encode($requestParams);


return $response;