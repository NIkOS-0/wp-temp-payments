<?php
/**
 * Custom Admin UI per role: Offer Manager
 */

// 1. Add capabilities to Offer Manager role
add_action('admin_init', function () {
    $role = get_role('offer_manager');
    if (!$role) return;

    $caps = [
        'upload_files',
        'manage_woocommerce',
        'edit_products',
        'edit_others_products',
        'publish_products',
        'read_product',
        'edit_product',
        'delete_product',
        'edit_product_offer',
        'edit_product_offers',
        'edit_others_product_offers',
        'publish_product_offers',
        'read_product_offer',
        'delete_product_offer',
        'edit_personal_offer',
        'edit_personal_offers',
        'edit_others_personal_offers',
        'publish_personal_offers',
        'read_personal_offer',
        'delete_personal_offer',
        'assign_product_terms',
    ];

    foreach ($caps as $cap) {
        if (!$role->has_cap($cap)) {
            $role->add_cap($cap);
        }
    }
});

// 2. Custom Login Logo
add_action('login_enqueue_scripts', function () {
    $logo_url = '/app/themes/ediet-theme/resources/images/logo.png';
    ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo $logo_url; ?>);
            height: 100px;
            width: 100%;
            background-size: contain;
            background-repeat: no-repeat;
            padding-bottom: 30px;
        }
        body.login {
            background: #fdfdfd;
        }
        .login #login_error, .login .message, .login .success {
            border-left: 4px solid #111;
        }
        .wp-core-ui .button-primary {
            background: #111;
            border-color: #111;
            color: #fff;
            text-decoration: none;
            text-shadow: none;
        }
    </style>
    <?php
});

add_filter('login_headerurl', function () {
    return home_url();
});

// 3. Admin UI Adjustments for Offer Manager
add_action('init', function () {
    if (!is_user_logged_in()) return;

    $user = wp_get_current_user();
    if (!in_array('offer_manager', $user->roles)) return;

    // Remove WP logo
    add_action('admin_bar_menu', function ($wp_admin_bar) {
        $wp_admin_bar->remove_node('wp-logo');
    }, 999);

    // Redirect to Dashboard on login
    add_filter('login_redirect', function ($redirect_to, $request, $user) {
        if (isset($user->roles) && is_array($user->roles)) {
            if (in_array('offer_manager', $user->roles)) {
                return admin_url('admin.php?page=offer-manager-dashboard');
            }
        }
        return $redirect_to;
    }, 10, 3);

    // Localization
    add_filter('locale', function ($locale) {
        return 'ru_RU';
    });

    // Redirect to custom dashboard from standard index.php
    add_action('admin_init', function () {
        global $pagenow;
        if ($pagenow === 'index.php' && !isset($_GET['page'])) {
            wp_safe_redirect(admin_url('admin.php?page=offer-manager-dashboard'));
            exit;
        }
    });

    // Hide unnecessary menu items
    add_action('admin_menu', function () {
        remove_menu_page('index.php'); // Standard dashboard
        remove_menu_page('tools.php');
        remove_menu_page('options-general.php');
        remove_menu_page('edit-comments.php');
        remove_menu_page('edit.php'); // Posts
    }, 999);
});

// Removed offer-manager-dashboard and render_offer_manager_dashboard since it was migrated to frontend

// 5. Global Admin Cleanup
add_action('admin_enqueue_scripts', function () {
    $user = wp_get_current_user();
    if (!in_array('offer_manager', $user->roles)) return;

    echo '<style>
        #footer-left { visibility: hidden; }
        #footer-upgrade { display: none; }
        .update-nag, .notice-info, .notice-warning { display: none !important; }
    </style>';
});


