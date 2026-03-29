<?php

namespace App;

/**
 * Register Offer Manager role and assign capabilities.
 */
add_action('init', function () {
    $role_name = 'offer_manager';
    $display_name = __('Offer Manager', 'sage');

    // Capabilities for Product Offers
    $product_caps = [
        'edit_product_offer',
        'read_product_offer',
        'delete_product_offer',
        'edit_product_offers',
        'edit_others_product_offers',
        'publish_product_offers',
        'read_private_product_offers',
        'delete_product_offers',
        'delete_private_product_offers',
        'delete_published_product_offers',
        'delete_others_product_offers',
        'edit_private_product_offers',
        'edit_published_product_offers',
    ];

    // Capabilities for Personal Offers
    $personal_caps = [
        'edit_personal_offer',
        'read_personal_offer',
        'delete_personal_offer',
        'edit_personal_offers',
        'edit_others_personal_offers',
        'publish_personal_offers',
        'read_private_personal_offers',
        'delete_personal_offers',
        'delete_private_personal_offers',
        'delete_published_personal_offers',
        'delete_others_personal_offers',
        'edit_private_personal_offers',
        'edit_published_personal_offers',
    ];

    $custom_caps = [
        'manage_offers'     => true,
        'read'              => true, // Required for admin access
        'edit_posts'        => true, // Required for duplicate_page plugin
        'edit_others_posts' => true, // Required for duplicate_page plugin
    ];

    $all_caps = array_merge(
        array_fill_keys($product_caps, true),
        array_fill_keys($personal_caps, true),
        $custom_caps
    );

    // Add or update the role
    $manager_role = get_role($role_name);
    if (!$manager_role) {
        add_role($role_name, $display_name, $all_caps);
    } else {
        foreach ($all_caps as $cap => $grant) {
            $manager_role->add_cap($cap);
        }
    }

    // Ensure administrator also has these capabilities
    $admin = get_role('administrator');
    if ($admin) {
        foreach ($all_caps as $cap => $grant) {
            $admin->add_cap($cap);
        }
    }
});



add_action('admin_menu', function () {
    $user = wp_get_current_user();

    if (!in_array('offer_manager', (array) $user->roles, true)) {
        return;
    }

    remove_menu_page('index.php');
    remove_menu_page('edit.php');          // Hide standard Posts
    remove_menu_page('edit-comments.php'); // Hide Comments
    remove_menu_page('wpseo_workouts');    // Hide Yoast SEO
}, 999);