<?php

return [
    'payfort' => [
        'service_command'=> 'TOKENIZATION',  // This should be TOKENIZATION
        'merchant_identifier' => 'abKSBKKe',
        'access_code' => 'QbRIlPcveY8j3Hv7CxNO',
        'SHAType' => 'SHA-256',
        'SHARequestPhrase' => 'YourTestSHARequestPhrase',
        'SHAResponsePhrase' => 'YourTestSHAResponsePhrase',
        'currency' => 'USD',
        'language' => 'en'
    ],
    'api_endpoints' => [
        'tokenization' => 'https://paymentservices.payfort.com/FortAPI/paymentApi',  // Correct endpoint
        'payment' => 'https://paymentservices.payfort.com/FortAPI/paymentApi'
    ],
    'return_url' => 'https://wafid.com/appointment/J1Z64WrGQkwnGq8/pay/'
];
