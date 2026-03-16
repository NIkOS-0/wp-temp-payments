<?php

namespace App\Fields;

/**
 * Register ACF fields for Products and Offers.
 */
add_action('acf/init', function () {
    // Product Fields
    acf_add_local_field_group([
        'key' => 'group_product_prices',
        'title' => __('Product Pricing', 'sage'),
        'fields' => [
            [
                'key' => 'field_product_prices',
                'label' => __('Price Options', 'sage'),
                'name' => 'price_options',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => __('Add Price Variant', 'sage'),
                'sub_fields' => [
                    [
                        'key' => 'field_price_label',
                        'label' => __('Label', 'sage'),
                        'name' => 'label',
                        'type' => 'text',
                        'placeholder' => __('Regular Price, Discounted etc.', 'sage'),
                    ],
                    [
                        'key' => 'field_price_value',
                        'label' => __('Price', 'sage'),
                        'name' => 'price',
                        'type' => 'number',
                    ],
                ],
            ],
            [
                'key' => 'field_product_short_desc',
                'label' => __('Short Description', 'sage'),
                'name' => 'short_description',
                'type' => 'textarea',
                'rows' => 3,
            ],
            [
                'key' => 'field_product_gallery',
                'label' => __('Product Gallery', 'sage'),
                'name' => 'product_gallery',
                'type' => 'gallery',
                'return_format' => 'id',
            ],
            [
                'key' => 'field_product_features',
                'label' => __('Key Features', 'sage'),
                'name' => 'product_features',
                'type' => 'repeater',
                'layout' => 'block',
                'button_label' => __('Add Feature', 'sage'),
                'sub_fields' => [
                    [
                        'key' => 'field_feature_title',
                        'label' => __('Title', 'sage'),
                        'name' => 'title',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_feature_desc',
                        'label' => __('Description', 'sage'),
                        'name' => 'description',
                        'type' => 'text',
                    ],
                ],
            ],
            [
                'key' => 'field_product_specs',
                'label' => __('Specifications', 'sage'),
                'name' => 'specifications',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => __('Add Spec', 'sage'),
                'sub_fields' => [
                    [
                        'key' => 'field_spec_name',
                        'label' => __('Name', 'sage'),
                        'name' => 'name',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_spec_value',
                        'label' => __('Value', 'sage'),
                        'name' => 'value',
                        'type' => 'text',
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'product_offer',
                ],
            ],
        ],
    ]);

    global $pagenow;
    $is_new_post = ($pagenow === 'post-new.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'personal_offer');

    // Personalized Offer Fields
    acf_add_local_field_group([
        'key' => 'group_personal_offer',
        'title' => __('Offer Configuration', 'sage'),
        'fields' => [
            [
                'key' => 'field_offer_product',
                'label' => __('Target Product', 'sage'),
                'name' => 'target_product',
                'type' => 'post_object',
                'post_type' => ['product_offer'],
                'allow_null' => 0,
                'multiple' => 0,
                'return_format' => 'id',
            ],
            [
                'key' => 'field_offer_internal_title',
                'label' => __('Internal Name', 'sage'),
                'name' => 'internal_title',
                'type' => 'text',
                'instructions' => __('For manager reference only.', 'sage'),
            ],
            [
                'key' => 'field_offer_price',
                'label' => __('Selected Price', 'sage'),
                'name' => 'selected_price',
                'type' => 'number',
                'instructions' => __('Calculated or manually entered price for this link.', 'sage'),
            ],
            [
                'key' => 'field_offer_expiry',
                'label' => __('Expiry (Hours)', 'sage'),
                'name' => 'expiry_hours',
                'type' => 'number',
                'default_value' => 24,
                'wrapper' => [
                    'class' => $is_new_post ? '' : 'acf-hidden',
                ],
            ],
            [
                'key' => 'field_offer_expiry_minutes',
                'label' => __('Expiry (Minutes)', 'sage'),
                'name' => 'expiry_minutes',
                'type' => 'number',
                'default_value' => 0,
                'wrapper' => [
                    'class' => $is_new_post ? '' : 'acf-hidden',
                ],
            ],
            [
                'key' => 'field_offer_security',
                'label' => __('Enable Cookie Binding', 'sage'),
                'name' => 'use_cookie_security',
                'type' => 'true_false',
                'default_value' => 1,
                'ui' => 1,
            ],
            [
                'key' => 'field_offer_cookie_hash',
                'label' => __('Bound Cookie Hash', 'sage'),
                'name' => 'cookie_hash',
                'type' => 'text',
                'readonly' => 1,
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'personal_offer',
                ],
            ],
        ],
    ]);
});
