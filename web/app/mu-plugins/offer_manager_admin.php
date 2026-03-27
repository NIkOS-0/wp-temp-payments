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

// 4. Custom Dashboard Page
add_action('admin_menu', function () {
    add_menu_page(
        'Дашборд',
        'Дашборд',
        'read',
        'offer-manager-dashboard',
        'render_offer_manager_dashboard',
        'dashicons-performance',
        2
    );
});

function render_offer_manager_dashboard() {
    global $wpdb;

    // Оптимизированный подсчет без загрузки всех постов в память
    $counts = $wpdb->get_results("SELECT post_status, COUNT(*) as cc FROM {$wpdb->posts} WHERE post_type = 'personal_offer' GROUP BY post_status");
    
    $total_offers = 0;
    $paid_offers = 0;

    if ($counts) {
        foreach ($counts as $row) {
            // Исключаем корзину и авто-черновики из "Всего"
            if (!in_array($row->post_status, ['trash', 'auto-draft'])) {
                $total_offers += (int) $row->cc;
            }
            if ($row->post_status === 'paid') {
                $paid_offers = (int) $row->cc;
            }
        }
    }
    
    $conversion = ($total_offers > 0) ? round(($paid_offers / $total_offers) * 100, 1) : 0;

    $recent_offers = get_posts([
        'post_type' => 'personal_offer',
        'numberposts' => 5,
        'post_status' => ['publish', 'paid'],
    ]);

    ?>
    <div class="wrap om-dashboard">
        <h1 class="wp-heading-inline">Панель управления менеджера</h1>
        <hr class="wp-header-end">

        <div class="om-stats-grid">
            <div class="om-stat-card">
                <div class="om-stat-label">Всего предложений</div>
                <div class="om-stat-value"><?php echo $total_offers; ?></div>
            </div>
            <div class="om-stat-card">
                <div class="om-stat-label">Оплачено</div>
                <div class="om-stat-value om-color-paid"><?php echo $paid_offers; ?></div>
            </div>
            <div class="om-stat-card">
                <div class="om-stat-label">Конверсия</div>
                <div class="om-stat-value"><?php echo $conversion; ?>%</div>
            </div>
        </div>

        <div class="om-content-grid">
            <div class="om-recent-activity">
                <h2>Последние предложения</h2>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Название</th>
                            <th>Статус</th>
                            <th>Дата</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_offers as $offer): 
                            $status = get_post_status($offer->ID);
                            $status_label = ($status === 'paid') ? 'Оплачено' : 'Активен';
                            $status_class = ($status === 'paid') ? 'om-tag-paid' : 'om-tag-active';
                        ?>
                            <tr>
                                <td><strong><a href="<?php echo get_edit_post_link($offer->ID); ?>"><?php echo get_the_title($offer->ID); ?></a></strong></td>
                                <td><span class="om-tag <?php echo $status_class; ?>"><?php echo $status_label; ?></span></td>
                                <td><?php echo get_the_date('d.m.Y H:i', $offer->ID); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($recent_offers)): ?>
                            <tr><td colspan="3">Предложений пока нет</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="om-quick-actions">
                <h2>Быстрые действия</h2>
                <div class="om-action-list">
                    <a href="<?php echo admin_url('admin.php?page=offer-manager-dashboard-link'); ?>" class="button button-primary button-hero">Создать предложение</a>
                    <a href="<?php echo admin_url('edit.php?post_type=product_offer'); ?>" class="button button-secondary">Управление товарами</a>
                </div>
            </div>
        </div>

        <style>
            .om-dashboard { margin-top: 20px; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif; }
            .om-stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
            .om-stat-card { background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; }
            .om-stat-label { color: #6b7280; font-size: 14px; margin-bottom: 8px; font-weight: 500; }
            .om-stat-value { font-size: 32px; font-weight: 700; color: #111827; }
            .om-color-paid { color: #059669; }
            
            .om-content-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }
            @media (max-width: 900px) { .om-content-grid { grid-template-columns: 1fr; } }
            
            .om-recent-activity, .om-quick-actions { background: #fff; padding: 20px; border-radius: 12px; border: 1px solid #e5e7eb; }
            .om-tag { padding: 4px 8px; border-radius: 9999px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
            .om-tag-paid { background: #d1fae5; color: #065f46; }
            .om-tag-active { background: #dbeafe; color: #1e40af; }
            
            .om-action-list { display: flex; flex-direction: column; gap: 10px; }
            .om-action-list .button { text-align: center; }

            /* Mob responsive tweaks */
            @media (max-width: 600px) {
                .om-stats-grid { grid-template-columns: 1fr; }
                .om-stat-card { text-align: center; }
            }
        </style>
    </div>
    <?php
}

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
