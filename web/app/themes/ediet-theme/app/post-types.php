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
        'capability_type' => 'product_offer',
        'map_meta_cap' => true,
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
        'supports' => ['title', 'custom-fields', 'author'],
        'capability_type' => 'personal_offer',
        'map_meta_cap' => true,
    ]);

    register_post_status('paid', [
        'label'                     => _x('Paid', 'post status', 'sage'),
        'label_count'               => _n_noop('Paid <span class="count">(%s)</span>', 'Paid <span class="count">(%s)</span>', 'sage'),
        'public'                    => true,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'exclude_from_search'       => false,
    ]);
});

/**
 * Add 'Paid' status to the post status dropdown in admin.
 */
add_action('admin_footer-post.php', function () {
    global $post;
    if ($post->post_type !== 'personal_offer') {
        return;
    }
    $status = get_post_status($post->ID);
    $selected = ($status === 'paid') ? 'selected="selected"' : '';
    $label = _x('Paid', 'post status', 'sage');
    echo "<script>
        jQuery(document).ready(function($){
            $('select#post_status').append('<option value=\"paid\" $selected>$label</option>');
            if ('$status' === 'paid') {
                $('.misc-pub-section.curtime').hide();
                $('#post-status-display').text('$label');
            }
        });
    </script>";
});

/**
 * Add custom columns to personal_offer list.
 */
add_filter('manage_personal_offer_posts_columns', function ($columns) {
    $new_columns = [];
    foreach ($columns as $key => $value) {
        if ($key === 'date') {
            $new_columns['offer_status'] = __('Status', 'sage');
            $new_columns['target_product'] = __('Product', 'sage');
            $new_columns['expiry_time'] = __('Expires In', 'sage');
            $new_columns['creator'] = __('Creator', 'sage');
        }
        $new_columns[$key] = $value;
    }
    return $new_columns;
});

/**
 * Render custom columns.
 */
add_action('manage_personal_offer_posts_custom_column', function ($column, $post_id) {
    switch ($column) {
        case 'offer_status':
            $status = get_post_status($post_id);
            $expiry = get_post_meta($post_id, '_expiry_timestamp', true);
            $is_expired = $expiry && time() > $expiry;
            
            if ($status === 'paid') {
                echo '<span class="status-paid" style="background: #c7e9c0; color: #1a4d1a; padding: 2px 8px; border-radius: 4px; font-weight: bold; text-transform: uppercase; font-size: 10px;">Оплачен</span>';
            } elseif ($is_expired) {
                echo '<span class="status-expired" style="background: #f7d7d7; color: #8b0000; padding: 2px 8px; border-radius: 4px; font-weight: bold; text-transform: uppercase; font-size: 10px;">Истек</span>';
            } else {
                echo '<span class="status-active" style="background: #d1ecf1; color: #0c5460; padding: 2px 8px; border-radius: 4px; font-weight: bold; text-transform: uppercase; font-size: 10px;">Активен</span>';
            }
            break;

        case 'target_product':
            $product_id = get_field('target_product', $post_id);
            if ($product_id) {
                echo '<a href="' . get_edit_post_link($product_id) . '">' . get_the_title($product_id) . '</a>';
            } else {
                echo '—';
            }
            break;

        case 'creator':
            $author_id = get_post_field('post_author', $post_id);
            if ($author_id) {
                echo get_the_author_meta('display_name', $author_id);
            } else {
                echo '—';
            }
            break;

        case 'expiry_time':
            $expiry = get_post_meta($post_id, '_expiry_timestamp', true);
            if ($expiry) {
                $remaining = $expiry - time();
                if ($remaining > 0) {
                    $h = floor($remaining / 3600);
                    $m = floor(($remaining % 3600) / 60);
                    echo $h . 'ч ' . $m . 'м';
                } else {
                    echo '0ч 0м';
                }
            } else {
                echo '—';
            }
            break;
    }
}, 10, 2);

/**
 * Remove 'View' action for personal_offer list.
 */
add_filter('post_row_actions', function ($actions, $post) {
    if ($post->post_type === 'personal_offer') {
        unset($actions['view']);
    }
    return $actions;
}, 10, 2);

/**
 * Hide unnecessary meta boxes for personal_offer.
 */
add_filter('post_submitbox_misc_actions', function () {
    global $post;
    if ($post->post_type === 'personal_offer') {
        ?>
        <style>
        .misc-pub-section.misc-pub-post-status,
        .misc-pub-section.misc-pub-visibility,
        .preview.button {
            display:none !important;
        }
        </style>
        <?php
    }
});

/**
 * Restrict author change for personal_offer to administrators only.
 */
add_action('admin_menu', function () {
    if (!current_user_can('administrator')) {
        remove_meta_box('authordiv', 'personal_offer', 'normal');
    }
});

/**
 * Force author to remain the same if not an administrator.
 */
add_filter('wp_insert_post_data', function ($data, $postarr) {
    if ($data['post_type'] === 'personal_offer' && !current_user_can('administrator')) {
        if (!empty($postarr['ID'])) {
            $current_post = get_post($postarr['ID']);
            $data['post_author'] = $current_post->post_author;
        }
    }
    return $data;
}, 10, 2);
