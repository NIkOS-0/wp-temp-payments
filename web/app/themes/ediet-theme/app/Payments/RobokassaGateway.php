<?php

namespace App\Payments;

use Exception;

class RobokassaGateway implements PaymentGatewayInterface
{
    protected string $merchantLogin;
    protected string $password1;
    protected string $isTest;

    public function __construct(array $config)
    {
        $this->merchantLogin = $config['robokassa_merchant_login'] ?? '';
        $this->password1 = $config['robokassa_password_1'] ?? '';
        $this->isTest = $config['robokassa_is_test'] ?? '1';

        if (empty($this->merchantLogin)) {
            throw new Exception("Robokassa configuration is missing.");
        }
    }

    private function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function createCheckoutUrl(int $offer_id, float $price, int $product_id): string
    {
        $out_summ = number_format($price, 2, '.', '');
        $inv_id = $offer_id . rand(100, 999);
        $inv_desc = "Оплата персонального предложения: " . get_the_title($product_id);
        
        $header = [
            'typ' => 'JWT',
            'alg' => 'MD5'
        ];

        $payload = [
            'MerchantLogin' => $this->merchantLogin,
            'InvoiceType' => 'OneTime',
            'Culture' => 'ru',
            'InvId' => (int) $inv_id,
            'OutSum' => (float) $out_summ,
            'Description' => $inv_desc,
            'IsTest' => (int) $this->isTest,
        ];

        $header_encoded = $this->base64url_encode(json_encode($header));
        $payload_encoded = $this->base64url_encode(json_encode($payload));

        $signature_data = $header_encoded . '.' . $payload_encoded;
        $secret_key = "{$this->merchantLogin}:{$this->password1}";
        
        // HMAC MD5
        $signature_hash = hash_hmac('md5', $signature_data, $secret_key, true);
        $signature_encoded = $this->base64url_encode($signature_hash);

        $jwt = $signature_data . '.' . $signature_encoded;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://services.robokassa.ru/InvoiceServiceWebApi/api/CreateInvoice");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '"' . $jwt . '"'); // Send JWT wrapped in quotes per docs
        
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            throw new Exception('Robokassa CURL error: ' . $error_msg);
        }

        curl_close($ch);

        $response = json_decode($result, true);

        if ($http_code < 200 || $http_code >= 300) {
            $error_msg = 'Robokassa Invoice API error';
            if (is_array($response) && isset($response['Message'])) {
                $error_msg = $response['Message'];
            } elseif (is_array($response) && isset($response['error'])) {
                 $error_msg = is_string($response['error']) ? $response['error'] : json_encode($response['error']);
            } elseif (is_string($result) && !empty($result)) {
                $error_msg = $result; // Sometimes APIs return plain text errors
            }
            throw new Exception($error_msg . ' HTTP: ' . $http_code);
        }

        // The response might be a direct string like "https://auth.robokassa.ru/invoice/..."
        // Or a JSON object like {"url": "..."} or {"invoiceUrl": "..."}
        if (is_string($response) && filter_var($response, FILTER_VALIDATE_URL)) {
            return $response;
        }

        if (is_array($response)) {
            if (isset($response['url'])) return $response['url'];
            if (isset($response['invoiceUrl'])) return $response['invoiceUrl'];
            if (isset($response['Url'])) return $response['Url'];
        }

        // Fallback: if json_decode failed but $result is a URL (some APIs return strings without quotes)
        $raw_result = trim($result, '"');
        if (filter_var($raw_result, FILTER_VALIDATE_URL)) {
            return $raw_result;
        }

        throw new Exception("Unable to parse Robokassa response: " . $result);
    }
}
