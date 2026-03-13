<?php

namespace App\Services;

class OfferService
{
    /**
     * Generate a unique personalized offer link.
     */
    public function createOffer($productId, $price, $expiryHours, $useCookieSecurity, $title = '', $expiryMinutes = 0)
    {
        $expiryTime = time() + ($expiryHours * HOUR_IN_SECONDS) + ($expiryMinutes * MINUTE_IN_SECONDS);
        $displayTitle = !empty($title) ? $title : 'Offer for Product #' . $productId . ' - ' . date('Y-m-d H:i');
        
        // Generate a random unique slug (token) for the URL
        $slug = 'offer-' . wp_generate_password(16, false);

        $offerId = wp_insert_post([
            'post_type' => 'personal_offer',
            'post_status' => 'publish',
            'post_title' => $displayTitle,
            'post_name'  => $slug,
        ]);

        if (is_wp_error($offerId)) {
            return $offerId;
        }

        update_field('target_product', $productId, $offerId);
        update_field('selected_price', $price, $offerId);
        update_field('expiry_hours', $expiryHours, $offerId);
        update_field('use_cookie_security', $useCookieSecurity, $offerId);
        update_field('internal_title', $title, $offerId);
        
        update_post_meta($offerId, '_expiry_timestamp', $expiryTime);

        return get_permalink($offerId);
    }

    /**
     * Get or create a stable persistent ID for the client.
     */
    public function getPersistentId()
    {
        $cookieName = 'ediet_persistent_client_id';
        $persistentId = $_COOKIE[$cookieName] ?? null;

        if (!$persistentId) {
            $persistentId = wp_generate_password(32, false);
            // Long lived cookie: 1 year
            setcookie($cookieName, $persistentId, time() + (1 * YEAR_IN_SECONDS), COOKIEPATH, COOKIE_DOMAIN);
        }

        return $persistentId;
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
            $currentPersistentId = $this->getPersistentId();
            $currentHash = md5($currentPersistentId);

            if (empty($savedHash)) {
                // First open: bind this offer to the current user's persistent ID
                update_field('cookie_hash', $currentHash, $offerId);
            } else {
                // Subsequent opens: check if current ID matches the bound hash
                if ($currentHash !== $savedHash) {
                    return ['valid' => false, 'reason' => 'security_violation'];
                }
            }
        }

        return ['valid' => true];
    }
}
