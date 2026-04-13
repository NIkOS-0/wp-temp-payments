<?php
/**
 * Plugin Name: Order Domain Logic Loader
 * Description: Wrapper/loader that restricts execution of order-related logic exclusively to the order domain.
 */

use function Env\env;

if (!function_exists('is_order_domain')) {
    function is_order_domain() {
        return ($_SERVER['HTTP_HOST'] ?? '') === env('ORDER_DOMAIN');
    }
}

if (is_order_domain()) {
    // We are on the order domain! Load the custom MU plugins from the order/ directory.
    
    // 1. Administration UI for Offer Manager
    if (file_exists(__DIR__ . '/order/offer_manager_admin.php')) {
        require_once __DIR__ . '/order/offer_manager_admin.php';
    }
    
    // 2. Custom 404 Handler (turns off non-order pages)
    if (file_exists(__DIR__ . '/order/offers_404.php')) {
        require_once __DIR__ . '/order/offers_404.php';
    }
    
    // 3. Payment Gateway API
    if (file_exists(__DIR__ . '/order/api_offers_payment/api_offers_payment.php')) {
        require_once __DIR__ . '/order/api_offers_payment/api_offers_payment.php';
    }
}
