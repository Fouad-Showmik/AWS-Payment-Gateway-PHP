<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$url = 'https://checkout.payfort.com/FortAPI/paymentPage';
$return_url='http://localhost/payment_process/purchase.php';
$arrData = array(

    'command'=> 'AUTHORIZATION',
    'access_code'=> 'QbRIlPcveY8j3Hv7CxNO',
    'merchant_identifier'=> 'abKSBKKe',
    'merchant_reference'=> 'Appointment-90506202232733715-kFxxzz6Rfu',
    'amount'=> 1000,
    'currency'=> 'USD',
    'language'=> 'en',
    'expiry_date'=>'02/2027',
    'card_number'=>'4937280016522755',
    'cvv'=>'592',
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