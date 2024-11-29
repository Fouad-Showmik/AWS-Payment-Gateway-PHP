<?php

$config = require 'config.php';
require 'vendor/autoload.php';
require 'payfortapi.php';
require 'creditcard.php';

$cardDetails = new CreditCard('4088325005275225', '02', '2027', '135');
$payfortAPI = new PayfortAPI($config);
$tokenResponse = $payfortAPI->createToken($cardDetails);

if ($tokenResponse && $tokenResponse['status'] == '14') {
    $token = $tokenResponse['token_name'];
    $paymentResponse = $payfortAPI->processPayment($cardDetails, 10000); // Example amount
    if ($paymentResponse['status'] == 'success') {
        echo "Payment processed successfully!";
    } else {
        echo "Payment failed: " . ($paymentResponse['message'] ?? 'Unknown error');
    }
} else {
    echo "Tokenization failed: " . ($tokenResponse['response_message'] ?? 'Unknown error');
}
