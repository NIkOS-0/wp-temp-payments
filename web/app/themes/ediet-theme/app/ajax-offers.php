<?php

namespace App;

use App\Services\OfferService;

/**
 * AJAX Handler for creating personalized offers.
 */
add_action('wp_ajax_create_personalized_offer', function () {
    // 1. Check nonce
    if (!check_ajax_referer('manager_dashboard_nonce', 'nonce', false)) {
        wp_send_json_error(['message' => 'Invalid nonce'], 403);
    }

    // 2. Check permissions
    if (!current_user_can('edit_posts')) {
        wp_send_json_error(['message' => 'Insufficient permissions'], 403);
    }

    $productId = intval($_POST['product_id'] ?? 0);
    $price = floatval($_POST['price'] ?? 0);
    $expiryHours = intval($_POST['expiry_hours'] ?? 24);
    $expiryMinutes = intval($_POST['expiry_minutes'] ?? 0);
    $useCookieSecurity = isset($_POST['use_cookie_security']);
    $offerTitle = sanitize_text_field($_POST['offer_title'] ?? '');

    if (!$productId || !$price) {
        wp_send_json_error(['message' => 'Missing required parameters']);
    }

    $offerService = new OfferService();
    $link = $offerService->createOffer($productId, $price, $expiryHours, $useCookieSecurity, $offerTitle, $expiryMinutes);

    if (is_wp_error($link)) {
        wp_send_json_error(['message' => $link->get_error_message()]);
    }

    wp_send_json_success(['link' => $link]);
});

/**
 * Security: Hook into individual offer pages to validate access.
 */
add_action('template_redirect', function () {
    if (!is_singular('personal_offer')) {
        return;
    }

    $offerId = get_the_ID();
    $offerService = new OfferService();
    $validation = $offerService->validateAccess($offerId);

    if (!$validation['valid']) {
        // Redirect to 404 or a custom "Link Expired" page
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        get_template_part('404');
        exit;
    }
});
