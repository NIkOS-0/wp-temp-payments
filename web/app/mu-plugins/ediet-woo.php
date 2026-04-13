<?php
/**
 * Plugin Name: e-Diet WooCommerce Customizations
 * Description: Custom Product Types, digital delivery, library tracking, and checkout customizations.
 */

// 1. Register Custom Product Types
add_filter('product_type_selector', function($types) {
    $types['book'] = 'Книга';
    $types['guide'] = 'Гайд';
    $types['mpo'] = 'МПО (Методичка)';
    $types['consultation'] = 'Консультация';
    return $types;
});

add_action('init', function() {
    if (!class_exists('WC_Product')) return;
    
    class WC_Product_Book extends WC_Product {
        public function __construct($product) {
            $this->product_type = 'book';
            parent::__construct($product);
        }
    }
    class WC_Product_Guide extends WC_Product {
        public function __construct($product) {
            $this->product_type = 'guide';
            parent::__construct($product);
        }
    }
    class WC_Product_Mpo extends WC_Product {
        public function __construct($product) {
            $this->product_type = 'mpo';
            parent::__construct($product);
        }
    }
    class WC_Product_Consultation extends WC_Product {
        public function __construct($product) {
            $this->product_type = 'consultation';
            parent::__construct($product);
        }
    }
});

add_filter('woocommerce_product_class', function($classname, $product_type) {
    if ($product_type === 'book') return 'WC_Product_Book';
    if ($product_type === 'guide') return 'WC_Product_Guide';
    if ($product_type === 'mpo') return 'WC_Product_Mpo';
    if ($product_type === 'consultation') return 'WC_Product_Consultation';
    return $classname;
}, 10, 2);

// 2. Add Custom Fields to Product Data
add_action('woocommerce_product_options_general_product_data', function() {
    echo '<div class="options_group show_if_consultation hidden">';
    woocommerce_wp_text_input([
        'id' => '_manager_phone',
        'label' => 'Телефон менеджера',
        'desc_tip' => true,
        'description' => 'Укажите телефон менеджера, который будет связываться с клиентом'
    ]);
    woocommerce_wp_text_input([
        'id' => '_manager_tg',
        'label' => 'Telegram менеджера'
    ]);
    echo '</div>';

    // Script to toggle fields
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($){
        $('select#product-type').on('change', function(){
            if ($(this).val() === 'consultation') {
                $('.show_if_consultation').show();
            } else {
                $('.show_if_consultation').hide();
            }
        }).trigger('change');
    });
    </script>
    <?php
});

add_action('woocommerce_process_product_meta', function($post_id) {
    if (isset($_POST['_manager_phone'])) update_post_meta($post_id, '_manager_phone', sanitize_text_field($_POST['_manager_phone']));
    if (isset($_POST['_manager_tg'])) update_post_meta($post_id, '_manager_tg', sanitize_text_field($_POST['_manager_tg']));
});

// 3. Checkout Defaults
add_filter('woocommerce_cart_needs_shipping', '__return_false');
add_filter('woocommerce_payment_complete_order_status', function($status, $order_id, $order) {
    if ($order && !$order->needs_shipping_address()) return 'completed';
    return $status;
}, 10, 3);
add_filter('woocommerce_checkout_registration_required', '__return_true');
add_filter('woocommerce_checkout_registration_enabled', '__return_true');
add_filter('pre_option_woocommerce_registration_generate_password', function() { return 'yes'; });

function ediet_generate_download_token($user_id, $product_id, $ttl = 86400) {
    return wp_hash_password($user_id . $product_id . time() . wp_rand());
}

// 4. Post-payment behavior based on product type
add_action('woocommerce_order_status_completed', function(int $order_id) {
    $order = wc_get_order($order_id);
    if (!$order) return;
    
    $user_id = $order->get_user_id();
    if (!$user_id) return; 

    foreach ($order->get_items() as $item) {
        $product = $item->get_product();
        if (!$product) continue;
        
        $type = $product->get_type();
        $product_id = $product->get_id();

        // Digital Products Behavior (Books, Guides, MPO)
        if (in_array($type, ['book', 'guide', 'mpo']) || $product->is_downloadable() || $product->is_virtual()) {
            $token = ediet_generate_download_token($user_id, $product_id, 86400); 
            $order->update_meta_data("_ediet_download_token_{$product_id}", $token);

            $library = get_user_meta($user_id, '_ediet_library', true) ?: [];
            $library[$product_id] = $token;
            update_user_meta($user_id, '_ediet_library', $library);
        }
        
        // Consultation Behavior
        if ($type === 'consultation') {
            // Consultation isn't added to materials, but logic could trigger calendar sync or P-102 specific email 
            $order->update_meta_data("_ediet_consultation_status_{$product_id}", 'pending_schedule');
        }
    }

    $order->save();
});

// 5. Account Endpoints (Library access)
add_action('init', function() { add_rewrite_endpoint('library', EP_PAGES); });

add_filter('woocommerce_account_menu_items', function($items) {
    $new_items = [];
    foreach ($items as $k => $v) {
        if ($k === 'downloads') continue; 
        $new_items[$k] = $v;
        if ($k === 'dashboard') $new_items['library'] = 'Мои материалы';
    }
    return $new_items;
});

add_action('woocommerce_account_library_endpoint', function() {
    $user_id = get_current_user_id();
    $library = get_user_meta($user_id, '_ediet_library', true) ?: [];
    
    echo '<h3>Мои материалы</h3>';
    if (empty($library)) {
        echo '<p>У вас пока нет доступных материалов.</p>';
        return;
    }

    echo '<div style="display:grid; gap:16px;">';
    foreach ($library as $product_id => $token) {
        $product = wc_get_product($product_id);
        if (!$product) continue;
        $download_url = home_url("/?download_token={$token}");
        echo '<div style="padding:20px; border:1px solid #eee; border-radius:12px; display:flex; justify-content:space-between; align-items:center; background:#fff;">';
        echo '<div><strong style="display:block; margin-bottom:5px;">' . esc_html($product->get_name()) . '</strong>';
        echo '<span style="font-size:12px; color:#666;">Доступ активен</span></div>';
        echo '<a href="' . esc_url($download_url) . '" target="_blank" style="padding:10px 20px; background:#FCC575; color:#111; border-radius:6px; text-decoration:none; font-weight:bold;">Скачать / Открыть</a>';
        echo '</div>';
    }
    echo '</div>';
});

add_action('template_redirect', function() {
    if (!empty($_GET['download_token'])) {
        $token = sanitize_text_field($_GET['download_token']);
        wp_die("Токен $token действителен. (Здесь начнется скачивание файла продукта).", "Скачивание", ['response' => 200]);
    }
});

add_filter('woocommerce_email_styles', function($css) {
    return $css . '.button { background-color: #FCC575 !important; color: #2C2C2C !important; } body { font-family: Inter, sans-serif !important; }';
});

// 6. Frontend Override for Consultations (Application form instead of Add to Cart)
add_action('woocommerce_single_product_summary', function() {
    global $product;
    if (!$product) return;
    
    if ($product->get_type() === 'consultation') {
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
        
        $phone = get_post_meta($product->get_id(), '_manager_phone', true) ?: '+7 (999) 000-00-00';
        $tg = get_post_meta($product->get_id(), '_manager_tg', true) ?: '@manager';
        
        echo '<div class="ediet-consultation-form" style="padding:20px; background:#f9fafb; border-radius:12px; margin-top:20px;">';
        echo '<h4 style="margin-top:0;">Оставить заявку на консультацию</h4>';
        echo '<p style="font-size:14px; margin-bottom:15px;">Перед оплатой наш менеджер свяжется с вами для выбора удобного времени.</p>';
        echo "<ul style='margin-bottom:15px; font-size:14px;'><li>Телефон: {$phone}</li><li>Telegram: {$tg}</li></ul>";
        echo '<button type="button" class="button alt" style="width:100%; border-radius:8px;" onclick="document.dispatchEvent(new Event(\'ediet-open-consultation-modal\'))">Оставить заявку</button>';
        echo '</div>';
    }
}, 29);
