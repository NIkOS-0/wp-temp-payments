<?php

namespace App\Payments;

use Exception;

class StripeGateway implements PaymentGatewayInterface
{
    protected string $secretKey;

    public function __construct(array $config)
    {
        $this->secretKey = $config['stripe_secret_key'] ?? '';
        if (empty($this->secretKey)) {
            throw new Exception("Stripe secret key is not configured.");
        }
    }

    public function createCheckoutUrl(int $offer_id, float $price, int $product_id): string
    {
        $success_url = get_permalink($offer_id) . (strpos(get_permalink($offer_id), '?') !== false ? '&' : '?') . 'stripe=success&session_id={CHECKOUT_SESSION_ID}';
        $cancel_url = get_permalink($offer_id) . (strpos(get_permalink($offer_id), '?') !== false ? '&' : '?') . 'stripe=cancel';

        $data = http_build_query([
            'payment_method_types[0]' => 'card',
            'line_items[0][price_data][currency]' => 'rub',
            'line_items[0][price_data][product_data][name]' => "Оплата персонального предложения: " . get_the_title($product_id),
            'line_items[0][price_data][unit_amount]' => $price * 100, // Stripe expects kopecks/cents
            'line_items[0][quantity]' => 1,
            'mode' => 'payment',
            'success_url' => $success_url,
            'cancel_url' => $cancel_url,
            'client_reference_id' => $offer_id,
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/checkout/sessions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_USERPWD, $this->secretKey . ':');

        $headers = ['Content-Type: application/x-www-form-urlencoded'];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            throw new Exception('CURL error: ' . $error_msg);
        }

        curl_close($ch);

        $response = json_decode($result, true);

        if ($http_code < 200 || $http_code >= 300) {
            throw new Exception($response['error']['message'] ?? 'Stripe API error');
        }

        return $response['url'];
    }
}
