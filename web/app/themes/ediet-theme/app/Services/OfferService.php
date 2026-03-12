<?php

namespace App\Services;

class OfferService
{
    /**
     * Generate a unique personalized offer link.
     */
    public function createOffer($productId, $price, $expiryHours, $useCookieSecurity)
    {
        $expiryTime = time() + ($expiryHours * HOUR_IN_SECONDS);
        
        $offerId = wp_insert_post([
            'post_type' => 'personal_offer',
            'post_status' => 'publish',
            'post_title' => 'Offer for Product #' . $productId . ' - ' . date('Y-m-d H:i'),
        ]);

        if (is_wp_error($offerId)) {
            return $offerId;
        }

        update_field('target_product', $productId, $offerId);
        update_field('selected_price', $price, $offerId);
        update_field('expiry_hours', $expiryHours, $offerId);
        update_field('use_cookie_security', $useCookieSecurity, $offerId);
        
        // We'll calculate expiration timestamp based on current time + hours
        update_post_meta($offerId, '_expiry_timestamp', $expiryTime);

        return get_permalink($offerId);
    }

    /**
     * Validate an offer link access.
     */
    public function validateAccess($offerId)
    {
        $expiryTimestamp = get_post_meta($offerId, '_expiry_timestamp', true);
        if ($expiryTimestamp && time() > $expiryTimestamp) {
            return ['valid' => false, 'reason' => 'expired'];
        }

        $useCookieSecurity = get_field('use_cookie_security', $offerId);
        if ($useCookieSecurity) {
            $savedHash = get_field('cookie_hash', $offerId);
            $currentCookie = $_COOKIE['offer_client_id'] ?? null;

            if (!$currentCookie) {
                // First visit - bind it
                $newHash = wp_generate_password(32, false);
                setcookie('offer_client_id', $newHash, time() + (YEAR_IN_SECONDS), COOKIEPATH, COOKIE_DOMAIN);
                update_field('cookie_hash', md5($newHash), $offerId);
            } else {
                // Secondary visit - check hash
                if (md5($currentCookie) !== $savedHash) {
                    return ['valid' => false, 'reason' => 'security_violation'];
                }
            }
        }

        return ['valid' => true];
    }
}
