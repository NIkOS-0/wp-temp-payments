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
        'manage_offers' => true,
        'read'         => true, // Required for admin access
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

/**
 * Add 'Manager Dashboard' link to the WP Admin sidebar for managers.
 */
add_action('admin_menu', function () {
    if (!current_user_can('manage_offers')) {
        return;
    }

    add_menu_page(
        'Offer generator',
        'Offer generator',
        'read',
        'offer-manager-dashboard-link',
        function () {
            wp_redirect(home_url('/manager-dashboard'));
            exit;
        },
        'dashicons-dashboard',
        0
    );
});

/**
 * Handle redirection for the manual menu page.
 */
add_action('admin_init', function () {
    if (is_admin() && isset($_GET['page']) && $_GET['page'] === 'offer-manager-dashboard-link') {
        wp_safe_redirect(home_url('/manager-dashboard'));
        exit;
    }
});

add_action('admin_menu', function () {
    $user = wp_get_current_user();

    if (!in_array('offer_manager', (array) $user->roles, true)) {
        return;
    }

    remove_menu_page('index.php');
}, 999);