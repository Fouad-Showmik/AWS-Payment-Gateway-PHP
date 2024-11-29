<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
$url = 'https://checkout.payfort.com/FortAPI/paymentPage';
$return_url='http://localhost/payment_process/new1.php';
$arrData = array(

    'service_command'=> 'AUTHORIZATION',
    'access_code'=> 'QbRIlPcveY8j3Hv7CxNO',
    'merchant_identifier'=> 'abKSBKKe',
    'merchant_reference'=> 'Appointment-90506202232733715-5xwMtZhxyD',
    'amount'=> 1000,
    'currency'=> 'USD',
    'language'=> 'en',
    'expiry_date'=>'02/2027',
    'card_number'=>'4088325005275225',
    'return_url'=>$return_url,
);


$shaString = '';
ksort($arrData);
foreach ($arrData as $key => $value) {
    $shaString .= "$key=$value";
}
// make sure to fill your sha request pass phrase
$shaString = "PASS" . $shaString . "PASS";
$signature = hash("sha256", $shaString);
// your request signature
echo $signature;
//===========================================================================
$merchant_reference = 'Appointment-90506202232733715';
$redirectUrl = 'https://paymentservices.payfort.com/FortAPI/paymentApi';
$return_url = 'https://wafid.com/appointment/J1Z64WrGQkwnGq8/pay/';
//https://checkout.payfort.com/FortAPI/paymentPage
$requestParams = array(
    'command' => 'PURCHASE',
    'access_code' => 'QbRIlPcveY8j3Hv7CxNO',
    'merchant_identifier' => 'abKSBKKe',
    'merchant_reference' => $merchant_reference,
    'amount' =>  1000, // Ensure amount is in minor units (fils)
    'currency' => 'USD',
    'language' => 'en',
    'customer_email' => "mutasim.fouad.showmik@gmail.com",
    'return_url' => $return_url,
    'card_number' => '4088325005275225', // Example card number (VISA)
    'expiry_month' => '02',
    'expiry_year' => '2027',
    'cvv' => '135',
    'signature'=>$signature,
);

// Calculate signature
$shaString = '';
ksort($requestParams);
//$SHARequestPhrase = '$2y$10$IGSCjOlk9';
//$SHAResponsePhrase = '$2y$10$IGSCjOlk9';
$SHARequestPhrase ='PASS';
$SHAResponsePhrase='PASS';
$SHAType = 'sha256';

foreach ($requestParams as $k => $v) {
    $shaString .= "$k=$v";
}

$shaString = $SHARequestPhrase . $shaString .$SHAResponsePhrase; // Corrected the concatenation
$signature = hash($SHAType, $shaString);

$requestParams['signature'] = $signature;

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
return $response;