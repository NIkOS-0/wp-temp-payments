<?php

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

$merchantLogin = 'E-Diet_Health_next';
$password1 = 'g4SfNr9T26J9cjsWjvfX';

$header_json = '{"typ":"JWT","alg":"MD5"}';
$payload = [
    'MerchantLogin' => $merchantLogin,
    'InvoiceType' => 'OneTime',
    'Culture' => 'ru',
    'OutSum' => (float)"100.00",
    'Description' => 'Order 99999',
    'SuccessUrl2Data' => [
        'Url' => 'https://dev.e-diet.wiki/api/payment/robokassa/success',
        'Method' => 'GET'
    ],
    'FailUrl2Data' => [
        'Url' => 'https://dev.e-diet.wiki/api/payment/robokassa/fail',
        'Method' => 'GET'
    ],
    'InvoiceItems' => [
        [
            'Name' => 'Товары для животных',
            'Quantity' => 1,
            'Cost' => 100.00,
            'Tax' => 'none',
            'PaymentMethod' => 'full_payment',
            'PaymentObject' => 'commodity'
        ]
    ]
];

$payload_json = json_encode($payload, JSON_UNESCAPED_UNICODE);

$header_encoded = base64url_encode($header_json);
$payload_encoded = base64url_encode($payload_json);

$signature_data = $header_encoded . '.' . $payload_encoded;
$secret_key = "{$merchantLogin}:{$password1}";

$signature_hash = hash_hmac('md5', $signature_data, $secret_key, true);
$signature_encoded = base64url_encode($signature_hash);

$jwt = $signature_data . '.' . $signature_encoded;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://services.robokassa.ru/InvoiceServiceWebApi/api/CreateInvoice");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, '"' . $jwt . '"'); 
        
$headers = [
    'Content-Type: application/json',
    'Accept: application/json'
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
echo "Result:\n";
var_dump($result);
