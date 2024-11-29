<?php

use GuzzleHttp\Client;

class PayfortAPI
{
    protected $config;
    protected $client;

    public function __construct($config)
    {
        $this->config = $config;
        $this->client = new Client();
    }

    public function sendRequest($endpoint, $data)
    {
        try {
            $response = $this->client->post($endpoint, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
            ]);

            $responseBody = $response->getBody()->getContents();
            $responseData = json_decode($responseBody, true);

            echo "<pre>Raw Response Body: " . htmlentities($responseBody) . "</pre>";

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new InvalidArgumentException('Invalid JSON response');
            }

            return $responseData;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function createToken($cardDetails)
    {
        $data = [
            'service_command' => 'CREATE_TOKEN',
            'access_code' => $this->config['payfort']['access_code'],
            'merchant_identifier' => $this->config['payfort']['merchant_identifier'],
            'language' => $this->config['payfort']['language'],
            'merchant_reference' => 'Appointment-90506202232733715-QcU6hTrOho',
            'amount' => $this->formatAmount(100.00),
            'currency' => $this->config['payfort']['currency'],
            'customer_email' => 'customer@gmail.com',
            'card_number' => $cardDetails->number,
            'expiry_month' => $cardDetails->expiry_month,
            'expiry_year' => $cardDetails->expiry_year,
            'cvv' => $cardDetails->cvv
        ];

        $data['signature'] = $this->generateSignature($data);

        $endpoint = $this->config['api_endpoints']['tokenization'];

        if (empty($endpoint)) {
            throw new InvalidArgumentException('Tokenization endpoint must be provided');
        }

        return $this->sendRequest($endpoint, $data);
    }

    public function processPayment($cardDetails, $amount)
    {
        $data = [
            'command' => 'PURCHASE',
            'access_code' => $this->config['payfort']['access_code'],
            'merchant_identifier' => $this->config['payfort']['merchant_identifier'],
            'merchant_reference' => 'Appointment-90506202232733715-QcU6hTrOho',
            'amount' => $this->formatAmount($amount),
            'currency' => $this->config['payfort']['currency'],
            'customer_email' => 'customer@gmail.com',
            'card_number' => $cardDetails->number,
            'expiry_month' => $cardDetails->expiry_month,
            'expiry_year' => $cardDetails->expiry_year,
            'cvv' => $cardDetails->cvv
        ];

        $data['signature'] = $this->generateSignature($data);

        $endpoint = $this->config['api_endpoints']['payment'];

        return $this->sendRequest($endpoint, $data);
    }

    protected function generateSignature($data)
    {
        ksort($data);
        $signatureString = $this->config['payfort']['SHARequestPhrase'];
        foreach ($data as $key => $value) {
            $signatureString .= "$key=$value";
        }
        $signatureString .= $this->config['payfort']['SHAResponsePhrase'];

        return hash('sha256', $signatureString);
    }

    protected function formatAmount($amount)
    {
        return number_format($amount, 2, '.', '');
    }
}
