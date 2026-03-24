<?php

namespace App;

use App\Payments\StripeGateway;
use App\Payments\RobokassaGateway;

/**
 * Handle Payment Checkout Session creation via AJAX.
 */
$create_payment_checkout = function () {
    // Only accept POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        wp_send_json_error('Method not allowed', 405);
    }

    $provider = filter_input(INPUT_POST, 'provider', FILTER_SANITIZE_STRING);
    $offer_id = filter_input(INPUT_POST, 'offer_id', FILTER_SANITIZE_NUMBER_INT);

    if (!$provider || !in_array($provider, ['stripe', 'robokassa'])) {
        wp_send_json_error(['message' => 'Invalid payment provider'], 400);
    }

    if (!$offer_id) {
        wp_send_json_error(['message' => 'Invalid offer ID'], 400);
    }

    $post = get_post($offer_id);
    if (!$post || $post->post_type !== 'personal_offer') {
        wp_send_json_error(['message' => 'Offer not found'], 404);
    }

    $product_id = get_field('target_product', $offer_id);
    if (!$product_id) {
        wp_send_json_error(['message' => 'Target product not found'], 400);
    }

    $price = get_field('selected_price', $offer_id);

    // Ensure price is valid
    if (!$price || !is_numeric($price)) {
        wp_send_json_error(['message' => 'Invalid price'], 400);
    }

    // Load payments environment securely
    $env_file = get_theme_file_path('.payments_env');
    $config = [];
    if (file_exists($env_file)) {
        $parsed = parse_ini_file($env_file);
        if ($parsed) {
            $config = [
                'stripe_secret_key' => $parsed['STRIPE_SECRET_KEY'] ?? '',
                'robokassa_merchant_login' => $parsed['ROBOKASSA_MERCHANT_LOGIN'] ?? '',
                'robokassa_password_1' => $parsed['ROBOKASSA_PASSWORD_1'] ?? '',
                'robokassa_password_2' => $parsed['ROBOKASSA_PASSWORD_2'] ?? '',
                'robokassa_is_test' => $parsed['ROBOKASSA_IS_TEST'] ?? '1',
            ];
        }
    } else {
        wp_send_json_error(['message' => 'Payment configuration missing'], 500);
    }

    try {
        if ($provider === 'stripe') {
            $gateway = new StripeGateway($config);
        } else {
            $gateway = new RobokassaGateway($config);
        }

        $url = $gateway->createCheckoutUrl((int) $offer_id, (float) $price, (int) $product_id);
        
        wp_send_json_success(['url' => $url]);

    } catch (\Exception $e) {
        wp_send_json_error(['message' => $e->getMessage()], 400);
    }
};

add_action('wp_ajax_create_payment_checkout', $create_payment_checkout);
add_action('wp_ajax_nopriv_create_payment_checkout', $create_payment_checkout);
