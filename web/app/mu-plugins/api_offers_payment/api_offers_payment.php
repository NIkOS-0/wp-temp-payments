<?php
/**
 * Plugin Name: API Offers Payment
 * Description: Core Payment REST API and Offer Transactions for Robokassa.
 * Version: 1.0.0
 * Author: E-Diet
 */

namespace App\OffersPayment;

use WP_REST_Request;
use WP_REST_Response;
use WP_Error;

require_once __DIR__ . '/RobokassaGateway.php';

class OffersPaymentPlugin
{
    public function __construct()
    {
        add_action('init', [$this, 'init']);
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function init()
    {
        // 1. Register transaction CPT
        register_post_type('offer_transaction', [
            'labels' => [
                'name' => __('Transactions', 'sage'),
                'singular_name' => __('Transaction', 'sage'),
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=personal_offer',
            'supports' => ['title', 'custom-fields'],
            'capability_type' => 'post',
        ]);

        // 2. Add Rewrite Rule to map /api/payment/robokassa/... to WP REST routes
        add_rewrite_rule(
            '^api/payment/robokassa/(checkout|result|success|fail)/?$',
            'index.php?rest_route=/payment/v1/robokassa/$matches[1]',
            'top'
        );
    }

    public function register_routes()
    {
        register_rest_route('payment/v1', '/robokassa/checkout', [
            'methods' => 'POST',
            'callback' => [$this, 'handle_checkout'],
            'permission_callback' => '__return_true', // public endpoint
        ]);

        register_rest_route('payment/v1', '/robokassa/result', [
            'methods' => ['GET', 'POST'],
            'callback' => [$this, 'handle_result'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route('payment/v1', '/robokassa/success', [
            'methods' => ['GET', 'POST'],
            'callback' => [$this, 'handle_success'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route('payment/v1', '/robokassa/fail', [
            'methods' => ['GET', 'POST'],
            'callback' => [$this, 'handle_fail'],
            'permission_callback' => '__return_true',
        ]);
    }

    public function handle_checkout(WP_REST_Request $request)
    {
        $offer_id = (int) $request->get_param('offer_id');
        
        if (!$offer_id) {
            return new WP_REST_Response(['message' => 'Invalid offer ID'], 400);
        }

        $post = get_post($offer_id);
        if (!$post || $post->post_type !== 'personal_offer') {
            return new WP_REST_Response(['message' => 'Offer not found'], 404);
        }
        
        // Ensure offer is not expired
        $expiry = get_post_meta($offer_id, '_expiry_timestamp', true);
        if ($expiry && time() > (int) $expiry) {
            return new WP_REST_Response(['message' => 'Offer expired'], 400);
        }

        // Check if already paid
        if ($post->post_status === 'paid') {
            return new WP_REST_Response(['message' => 'Offer already paid'], 400);
        }

        $product_id = get_post_meta($offer_id, 'target_product', true);
        $price = get_post_meta($offer_id, 'selected_price', true);

        if (!$price || !is_numeric($price)) {
            return new WP_REST_Response(['message' => 'Invalid price'], 400);
        }

        // Create transaction
        $transaction_id = wp_insert_post([
            'post_type' => 'offer_transaction',
            'post_status' => 'pending',
            'post_title' => "Transaction for Offer #$offer_id"
        ]);

        if (is_wp_error($transaction_id)) {
            return new WP_REST_Response(['message' => 'Could not create transaction'], 500);
        }

        update_post_meta($transaction_id, '_offer_id', $offer_id);
        update_post_meta($transaction_id, '_amount', $price);
        update_post_meta($transaction_id, '_provider', 'robokassa');

        $gateway = new RobokassaGateway();
        
        try {
            $product_name = get_the_title($product_id);
            $description = "Оплата персонального предложения: " . $product_name;
            $url = $gateway->createCheckoutUrl($transaction_id, (float) $price, $description);
            
            return new WP_REST_Response(['url' => $url], 200);
        } catch (\Exception $e) {
            wp_update_post([
                'ID' => $transaction_id,
                'post_status' => 'failed'
            ]);
            return new WP_REST_Response(['message' => $e->getMessage()], 500);
        }
    }

    public function handle_result(WP_REST_Request $request)
    {
        $out_sum = $request->get_param('OutSum');
        $inv_id = $request->get_param('InvId');
        $signature = $request->get_param('SignatureValue');

        if (!$out_sum || !$inv_id || !$signature) {
            return new WP_REST_Response("Bad Request", 400);
        }

        $gateway = new RobokassaGateway();
        if (!$gateway->verifyResultSignature($out_sum, $inv_id, $signature)) {
            return new WP_REST_Response("Invalid signature", 400);
        }

        $transaction = get_post($inv_id);
        if (!$transaction || $transaction->post_type !== 'offer_transaction') {
            return new WP_REST_Response("Transaction not found", 404);
        }

        // Optional verify amount:
        // $expected_sum = (float) get_post_meta($inv_id, '_amount', true);
        // if (abs((float)$out_sum - $expected_sum) > 0.01) { return ... }

        if ($transaction->post_status === 'success') {
            return new WP_REST_Response("OK$inv_id", 200);
        }

        // Mark transaction successful
        wp_update_post([
            'ID' => $inv_id,
            'post_status' => 'success'
        ]);

        // Mark offer as paid
        $offer_id = get_post_meta($inv_id, '_offer_id', true);
        if ($offer_id) {
            wp_update_post([
                'ID' => $offer_id,
                'post_status' => 'paid'
            ]);
        }

        return new WP_REST_Response("OK$inv_id", 200);
    }

    public function handle_success(WP_REST_Request $request)
    {
        $inv_id = $request->get_param('InvId');
        $offer_id = get_post_meta($inv_id, '_offer_id', true);
        
        // Let's redirect them to a success page
        $success_url = home_url("/thank-you/");
        if ($offer_id) {
            $success_url = get_permalink($offer_id);
        }
        
        wp_redirect(add_query_arg('payment', 'success', $success_url));
        exit;
    }

    public function handle_fail(WP_REST_Request $request)
    {
        $inv_id = $request->get_param('InvId');
        $offer_id = get_post_meta($inv_id, '_offer_id', true);
        
        $fail_url = home_url("/");
        if ($offer_id) {
            $fail_url = get_permalink($offer_id);
        }

        wp_redirect(add_query_arg('payment', 'fail', $fail_url));
        exit;
    }
}

new OffersPaymentPlugin();
