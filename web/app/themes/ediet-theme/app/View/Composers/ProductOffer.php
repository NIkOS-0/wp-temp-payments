<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class ProductOffer extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'single-personal_offer',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        $offerId = get_the_ID();
        $productId = get_field('target_product', $offerId);
        $selectedPrice = get_field('selected_price', $offerId);
        
        return [
            'product' => get_post($productId),
            'price' => $selectedPrice,
            'offer_id' => $offerId,
            'thumbnail' => get_the_post_thumbnail_url($productId, 'large'),
            'content' => apply_filters('the_content', get_post_field('post_content', $productId)),
            'short_description' => get_field('short_description', $productId),
            'gallery' => get_field('product_gallery', $productId),
            'features' => get_field('product_features', $productId),
            'specs' => get_field('specifications', $productId),
            'expiry_time' => date('H:i', (int) get_post_meta($offerId, '_expiry_timestamp', true)),
            'expiry_timestamp' => (int) get_post_meta($offerId, '_expiry_timestamp', true),
        ];
    }
}
