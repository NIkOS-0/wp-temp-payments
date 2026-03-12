<?php

namespace App;

/**
 * Register Custom Post Types.
 */
add_action('init', function () {
    register_post_type('product_offer', [
        'labels' => [
            'name' => __('Products (Offers)', 'sage'),
            'singular_name' => __('Product', 'sage'),
        ],
        'public' => true,
        'show_ui' => true,
        'has_archive' => false,
        'show_in_rest' => true,
        'exclude_from_search' => true,
        'publicly_queryable' => false, // Only accessible via personalized link
        'menu_icon' => 'dashicons-cart',
        'supports' => ['title', 'editor', 'thumbnail'],
    ]);

    register_post_type('personal_offer', [
        'labels' => [
            'name' => __('Personalized Offers', 'sage'),
            'singular_name' => __('Personalized Offer', 'sage'),
        ],
        'public' => true,
        'show_ui' => true,
        'has_archive' => false,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'p'], // short slug for links
        'exclude_from_search' => true,
        'publicly_queryable' => true,
        'menu_icon' => 'dashicons-share-alt',
        'supports' => ['title'],
        'capability_type' => 'post',
    ]);
});
