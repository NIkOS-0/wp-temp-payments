<?php
/**
 * Custom Admin UI per role: Offer Manager
 */

// Увеличиваем время жизни сессии до 14 дней, чтобы менеджеров не "выкидывало" из админки
add_filter('auth_cookie_expiration', function ($expirein) {
    return 14 * DAY_IN_SECONDS;
});

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

    // Remove WP logo and profile links
    add_action('admin_bar_menu', function ($wp_admin_bar) {
        $wp_admin_bar->remove_node('wp-logo');
        $wp_admin_bar->remove_node('edit-profile');
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

    // Redirect to custom dashboard from standard index.php and profile.php
    add_action('admin_init', function () {
        global $pagenow;
        if (in_array($pagenow, ['index.php', 'profile.php']) && !isset($_GET['page'])) {
            wp_safe_redirect(admin_url('admin.php?page=offer-manager-dashboard'));
            exit;
        }
    });

    // Hide unnecessary menu items
    add_action('admin_menu', function () {
        remove_menu_page('index.php'); // Standard dashboard
        remove_menu_page('profile.php'); // Убираем доступ к профилю
        remove_menu_page('tools.php');
        remove_menu_page('options-general.php');
        remove_menu_page('edit-comments.php');
        remove_menu_page('edit.php'); // Posts
    }, 999);
});

// 3c. Localize product_offer editor & replace "Back" button with Dashboard link
add_action('admin_footer', function () {
    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== 'product_offer') return;

    $dashboard_url = esc_url(admin_url('admin.php?page=offer-manager-dashboard'));
    echo "<script>
    (function() {
        const DASHBOARD_URL = '{$dashboard_url}';

        const labels = {
            'Product Gallery': 'Галерея товара',
            'Label': 'Название варианта',
            'price': 'Цена (₽)',
            'Price': 'Цена (₽)',
            'Add Price Variant': '+ Добавить вариант цены',
            'Key Features': 'Ключевые характеристики',
            'Add Feature': '+ Добавить характеристику',
            'Specifications': 'Спецификации',
            'Name': 'Параметр',
            'Value': 'Значение',
            'Add Spec': '+ Добавить спецификацию',
            'Add Row': '+ Добавить строку',
            'Add to gallery': 'Добавить в галерею',
            'Short Description': 'Краткое описание',
            'Description': 'Описание',
            'Product pricing': 'Цена товара',
            'Price Options': 'Варианты цен',
            'Excerpt': 'Краткое описание',
            'Post': 'Запись',
            'Category': 'Категория',
            'Tags': 'Метки',
            'Title': 'Название',
            'Featured Image': 'Изображение товара',
            'Discussion': 'Обсуждение',
            'Revisions': 'Ревизии',
            'Slug': 'Постоянная ссылка',
            'Author': 'Автор',
            'Published': 'Опубликовано',
            'Draft': 'Черновик',
            'Pending Review': 'На рассмотрении',
            'Publish': 'Опубликовать',
            'Save as pending': 'Отправить на рассмотрение',
            'Move to Trash': 'Удалить',
            'Visibility': 'Видимость',
            'Public': 'Публичный',
            'Private': 'Приватный',
            'Password protected': 'Защищено паролем',
        };

        function patchUI() {
            // 1. Replace native back button destination
            document.querySelectorAll('.editor-header__back, .editor-header__back-button').forEach(function(backBtn) {
                if (backBtn.dataset.omDone) return;
                backBtn.dataset.omDone = '1';
                backBtn.setAttribute('href', DASHBOARD_URL);
                // onclick override for <button> elements that don't use href
                backBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    window.location.href = DASHBOARD_URL;
                }, true);
                // Replace visible label text
                backBtn.querySelectorAll('span, .editor-header__back-button-label').forEach(function(s) {
                    if (s.childElementCount === 0 && s.textContent.trim()) {
                        s.textContent = 'Дашборд';
                    }
                });
            });

            // 2. Translate labels
            document.querySelectorAll(
                '.components-panel__body-title, label, .components-base-control__label, h2, h3, button, .acf-label label, .acf-button'
            ).forEach(function(el) {
                var text = el.childNodes[0];
                if (text && text.nodeType === Node.TEXT_NODE) {
                    var trimmed = text.textContent.trim();
                    if (labels[trimmed]) {
                        text.textContent = text.textContent.replace(trimmed, labels[trimmed]);
                    }
                }
            });
        }

        // Run once DOM is ready, then watch for React re-renders
        document.addEventListener('DOMContentLoaded', function() {
            patchUI();
            var observer = new MutationObserver(patchUI);
            observer.observe(document.body, { childList: true, subtree: true });
        });
    })();
    </script>";
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

    $products = get_posts([
        'post_type' => 'product_offer',
        'numberposts' => -1,
        'post_status' => 'publish',
    ]);

    // Data Aggregation for Chart.js (Last 30 Days)
    $days_data = [];
    $current_time = time();
    $thirty_days_ago_date = wp_date('Y-m-d', strtotime('-29 days'));

    for ($i = 29; $i >= 0; $i--) {
        $date = wp_date('d.m', strtotime("-$i days"));
        $full_date = wp_date('Y-m-d', strtotime("-$i days"));
        $days_data[$full_date] = ['label' => $date, 'total' => 0, 'paid' => 0, 'expired' => 0];
    }

    $chart_query = $wpdb->prepare("
        SELECT 
            DATE(p.post_date) as create_date,
            p.post_status,
            m.meta_value as expiry_time
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->postmeta} m ON p.ID = m.post_id AND m.meta_key = '_expiry_timestamp'
        WHERE p.post_type = 'personal_offer' 
          AND p.post_date >= %s
          AND p.post_status NOT IN ('trash', 'auto-draft')
    ", $thirty_days_ago_date . ' 00:00:00');
    
    $chart_results = $wpdb->get_results($chart_query);

    if ($chart_results) {
        foreach ($chart_results as $row) {
            $date = $row->create_date;
            if (isset($days_data[$date])) {
                $days_data[$date]['total']++;
                
                if ($row->post_status === 'paid') {
                    $days_data[$date]['paid']++;
                } elseif (!empty($row->expiry_time) && $current_time > (int)$row->expiry_time) {
                    $days_data[$date]['expired']++;
                }
            }
        }
    }

    $chart_labels = wp_json_encode(array_column($days_data, 'label'));
    $chart_total = wp_json_encode(array_column($days_data, 'total'));
    $chart_paid = wp_json_encode(array_column($days_data, 'paid'));
    $chart_expired = wp_json_encode(array_column($days_data, 'expired'));

    ?>
    <div class="wrap tw-isolate" style="margin: 20px 20px 0 2px;">
        <style>
            /* Изолируем Tailwind стили, чтобы они не ломали WP Admin */
            .tw-isolate * { font-family: inherit; box-sizing: border-box; }
            .tw-isolate input[type=text], .tw-isolate input[type=number], .tw-isolate select { box-shadow: none !important; border: none !important; outline: none !important; margin: 0; }
            .tw-isolate input[type=checkbox], .tw-isolate input[type=radio] { box-shadow: none !important; border: none !important; min-width: 20px; min-height: 20px; cursor: pointer; margin: 0; }
            .tw-isolate label { margin: 0; padding: 0; }
            /* Сброс WP стилей для Tailwind a */
            .tw-isolate a { text-decoration: none !important; }
            /* Hide WP notices in this UI */
            .update-nag, .notice { display: none !important; }
        </style>

        <!-- Подключаем Tailwind для UI блока всего дашборда -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                corePlugins: {
                    preflight: false,
                }
            }
        </script>

        <div class="max-w-6xl mx-auto py-2 px-2 sm:px-6">
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900 uppercase m-0 leading-none" style="padding:0;">Панель управления</h1>
                    <p class="text-xs font-bold font-white text-slate-400 uppercase tracking-widest mt-1 mb-0">Менеджер предложений</p>
                </div>
                <a href="<?php echo admin_url('edit.php?post_type=product_offer'); ?>" class="inline-flex items-center justify-center px-6 py-3 bg-white border border-slate-200 text-slate-700 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-slate-50 hover:text-blue-600 transition-all shadow-sm whitespace-nowrap cursor-pointer">Управление товарами &rarr;</a>
            </div>

            <!-- 30 Days Activity Chart (Hidden on mobile) -->
            <div class="hidden md:block bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 mb-8 w-full">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-sm font-black text-slate-900 tracking-tight uppercase leading-none m-0 pt-0">Активность (30 дней)</h2>
                    <div class="flex space-x-5">
                        <div class="flex items-center space-x-2"><div class="w-3 h-3 rounded-full bg-slate-800"></div><span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0 mb-0 pt-0 leading-none">Всего</span></div>
                        <div class="flex items-center space-x-2"><div class="w-3 h-3 rounded-full bg-emerald-500"></div><span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0 mb-0 pt-0 leading-none">Оплачено</span></div>
                        <div class="flex items-center space-x-2"><div class="w-3 h-3 rounded-full bg-red-500"></div><span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0 mb-0 pt-0 leading-none">Истекли</span></div>
                    </div>
                </div>
                <!-- Fixed Height Wrapper for Chart.js -->
                <div class="relative w-full h-56">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const ctx = document.getElementById('activityChart');
                    if (ctx) {
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: <?php echo $chart_labels; ?>,
                                datasets: [
                                    {
                                        label: 'Создано',
                                        data: <?php echo $chart_total; ?>,
                                        borderColor: '#1e293b', // slate-800
                                        backgroundColor: 'rgba(30, 41, 59, 0.05)',
                                        borderWidth: 2,
                                        pointRadius: 3,
                                        pointHoverRadius: 5,
                                        tension: 0.4,
                                        fill: true
                                    },
                                    {
                                        label: 'Оплачено',
                                        data: <?php echo $chart_paid; ?>,
                                        borderColor: '#10b981', // emerald-500
                                        backgroundColor: 'transparent',
                                        borderWidth: 2,
                                        pointRadius: 0,
                                        pointHoverRadius: 4,
                                        tension: 0.4
                                    },
                                    {
                                        label: 'Истекли',
                                        data: <?php echo $chart_expired; ?>,
                                        borderColor: '#ef4444', // red-500
                                        backgroundColor: 'transparent',
                                        borderWidth: 2,
                                        pointRadius: 0,
                                        pointHoverRadius: 4,
                                        borderDash: [5, 5],
                                        tension: 0.4
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        mode: 'index',
                                        intersect: false,
                                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                        titleFont: { size: 11, family: 'sans-serif' },
                                        bodyFont: { size: 12, family: 'sans-serif', weight: 'bold' },
                                        padding: 12,
                                        cornerRadius: 8,
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: { display: false, drawBorder: false },
                                        ticks: { font: { size: 10 }, color: '#94a3b8' }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        border: { display: false },
                                        grid: { color: '#f1f5f9' },
                                        ticks: { precision: 0, font: { size: 10 }, color: '#94a3b8', padding: 10 }
                                    }
                                },
                                interaction: { mode: 'nearest', axis: 'x', intersect: false }
                            }
                        });
                    }
                });
            </script>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 flex flex-col justify-center items-center sm:items-start text-center sm:text-left transition-transform hover:-translate-y-1">
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Всего предложений</div>
                    <div class="text-4xl font-black text-slate-900 leading-none"><?php echo $total_offers; ?></div>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 flex flex-col justify-center items-center sm:items-start text-center sm:text-left transition-transform hover:-translate-y-1">
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Оплачено</div>
                    <div class="text-4xl font-black text-emerald-600 leading-none"><?php echo $paid_offers; ?></div>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 flex flex-col justify-center items-center sm:items-start text-center sm:text-left transition-transform hover:-translate-y-1">
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Конверсия</div>
                    <div class="text-4xl font-black text-blue-600 leading-none"><?php echo $conversion; ?>%</div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- Generator Form (2 cols) -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 relative overflow-hidden">

                        <h2 class="text-xl font-black text-slate-900 tracking-tight uppercase leading-none mb-6 relative z-10 m-0">Создать предложение</h2>
                        
                        <form id="create-offer-form" class="space-y-6 relative z-10 w-full m-0">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                              <div class="space-y-2">
                                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest ml-1">Название оффера</label>
                                <input type="text" name="offer_title" placeholder="Напр. Скидка для Ивана" class="w-full p-4 bg-slate-50 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-800 placeholder:text-slate-300 m-0">
                              </div>

                              <?php if (!empty($products)): ?>
                                <div class="space-y-2">
                                  <label class="block text-xs font-black text-slate-700 uppercase tracking-widest ml-1">Товар</label>
                                  <select name="product_id" id="product_id" class="w-full p-4 bg-slate-50 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-bold text-slate-800 m-0" required>
                                    <option value="">-- Выбрать товар --</option>
                                    <?php foreach ($products as $p): ?>
                                      <option value="<?php echo $p->ID; ?>" data-prices='<?php echo esc_attr(json_encode(get_field("price_options", $p->ID) ?: [])); ?>'>
                                        <?php echo esc_html($p->post_title); ?>
                                      </option>
                                    <?php endforeach; ?>
                                  </select>
                                </div>
                              <?php endif; ?>
                            </div>

                            <!-- Quantity Field -->
                            <div id="quantity-wrapper" class="hidden animate-in fade-in slide-in-from-top-4 duration-300">
                               <label class="block text-xs font-black text-slate-700 uppercase tracking-widest ml-1 mb-2">Количество упаковок</label>
                               <div class="flex items-center gap-3">
                                   <div class="flex items-center bg-slate-50 rounded-2xl border-2 border-slate-100 overflow-hidden focus-within:border-blue-200 transition-all">
                                       <button type="button" id="qty-down" class="w-11 h-11 flex items-center justify-center text-slate-600 hover:bg-slate-200 hover:text-slate-900 transition-all font-black text-lg border-none cursor-pointer bg-transparent">−</button>
                                       <input type="number" name="offer_quantity" id="offer_quantity" value="1" min="1" max="50" class="w-16 text-center p-2 bg-transparent focus:ring-0 font-black text-slate-900 text-base m-0 border-none outline-none">
                                       <button type="button" id="qty-up" class="w-11 h-11 flex items-center justify-center text-slate-600 hover:bg-slate-200 hover:text-slate-900 transition-all font-black text-lg border-none cursor-pointer bg-transparent">+</button>
                                   </div>
                                   <div class="text-sm font-bold text-slate-500">упаковок · <span class="text-blue-600 font-black" id="qty-total-display"></span></div>
                               </div>
                            </div>

                            <div id="price-variants" class="hidden animate-in fade-in slide-in-from-top-4 duration-300">
                                   <label class="block text-xs font-black text-slate-700 uppercase tracking-widest ml-1 mb-2">Выберите цену</label>
                               <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="price-list"></div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-center">
                              <div class="space-y-2">
                                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest ml-1">Срок действия</label>
                                <div class="flex items-center space-x-4">
                                  <div class="flex items-center space-x-2">
                                    <input type="number" name="expiry_hours" value="24" class="w-24 p-4 bg-slate-50 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-black text-slate-800 text-center m-0">
                                    <span class="text-[10px] font-black text-slate-900 uppercase tracking-widest">час.</span>
                                  </div>
                                  <div class="flex items-center space-x-2">
                                    <input type="number" name="expiry_minutes" value="0" class="w-24 p-4 bg-slate-50 rounded-2xl focus:ring-2 focus:ring-blue-100 transition-all font-black text-slate-800 text-center m-0">
                                    <span class="text-[10px] font-black text-slate-900 uppercase tracking-widest">мин.</span>
                                  </div>
                                </div>
                              </div>

                              <div class="flex items-center space-x-3 bg-slate-50 p-4 rounded-2xl border border-transparent hover:border-blue-200 transition-all cursor-pointer group" onclick="document.getElementById('use_cookie_security').click()">
                                <input type="checkbox" name="use_cookie_security" id="use_cookie_security" checked class="w-6 h-6 text-blue-600 bg-white rounded-lg focus:ring-blue-500 pointer-events-none m-0">
                                <label class="text-xs font-black text-slate-600 uppercase tracking-tight leading-none cursor-pointer m-0 pt-0">Привязать к браузеру</label>
                              </div>
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="w-full bg-slate-900 hover:bg-blue-600 text-white font-black py-4 px-6 rounded-xll transition-all shadow-xl hover:shadow-blue-500/40 text-sm uppercase tracking-widest active:scale-[0.98] border-none cursor-pointer flex justify-center items-center m-0 rounded-2xl">
                                  Создать предложение
                                </button>
                            </div>
                        </form>

                        <div id="offer-result" class="mt-8 hidden p-6 bg-blue-600 rounded-3xl animate-in zoom-in-95 duration-500 shadow-xl shadow-blue-500/30 w-full mb-0">
                           <p class="text-[10px] text-white/80 font-black uppercase tracking-widest mb-3 m-0">Ссылка готова:</p>
                           <div class="flex flex-col sm:flex-row items-center gap-3">
                             <input type="text" id="generated-link" readonly class="w-full p-4 bg-white/10 border border-white/20 rounded-2xl text-white font-mono text-xs focus:ring-0 placeholder:text-white/30 m-0">
                             <button onclick="copyLink(event)" class="w-full sm:w-auto bg-white text-blue-600 px-6 py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-all shadow-md active:scale-[0.98] border-none m-0 cursor-pointer text-[11px] whitespace-nowrap">
                               Копировать
                             </button>
                           </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity (1 col) -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-sm font-black text-slate-900 tracking-tight uppercase leading-none m-0">Последние ссылки</h2>
                        </div>
                        <div class="flex flex-col space-y-3">
                            <?php foreach ($recent_offers as $offer): 
                                $status = get_post_status($offer->ID);
                                $is_paid = ($status === 'paid');
                                $status_label = $is_paid ? 'Оплачено' : 'Активен';
                                $bg_class = $is_paid ? 'bg-emerald-50' : 'bg-blue-50';
                                $text_class = $is_paid ? 'text-emerald-700' : 'text-blue-700';
                            ?>
                                <a href="<?php echo get_edit_post_link($offer->ID); ?>" class="group block p-4 rounded-2xl border border-slate-100 hover:border-blue-200 hover:bg-slate-50 transition-all cursor-pointer">
                                    <div class="font-bold text-slate-800 text-sm mb-3 group-hover:text-blue-600 transition-colors leading-tight"><?php echo esc_html(get_the_title($offer->ID)); ?></div>
                                    <div class="flex justify-between items-center">
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest <?php echo $bg_class . ' ' . $text_class; ?>"><?php echo $status_label; ?></span>
                                        <span class="text-[10px] font-bold text-slate-400 font-mono"><?php echo get_the_date('d.m.Y', $offer->ID); ?></span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                            <?php if (empty($recent_offers)): ?>
                                <div class="text-center p-6 text-sm font-bold text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200">Предложений пока нет</div>
                            <?php endif; ?>
                            
                            <?php if (count($recent_offers) >= 5): ?>
                                <a href="<?php echo admin_url('edit.php?post_type=personal_offer'); ?>" class="block text-center mt-2 text-xs font-black text-slate-400 uppercase tracking-widest hover:text-blue-600 transition-colors">Все предложения &rarr;</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
        <script>
            const form = document.getElementById('create-offer-form');
            const productSelect = document.getElementById('product_id');
            const priceVariants = document.getElementById('price-variants');
            const priceList = document.getElementById('price-list');
            const resultDiv = document.getElementById('offer-result');
            const generatedLink = document.getElementById('generated-link');
            const quantityWrapper = document.getElementById('quantity-wrapper');
            const qtyInput = document.getElementById('offer_quantity');
            const qtyDown = document.getElementById('qty-down');
            const qtyUp = document.getElementById('qty-up');
            const qtyTotalDisplay = document.getElementById('qty-total-display');


            if(productSelect) {
                productSelect.addEventListener('change', function() {
                  const selected = this.options[this.selectedIndex];
                  const prices = JSON.parse(selected.dataset.prices || '[]');
                  
                  priceList.innerHTML = '';
                  if (prices.length > 0) {
                    priceVariants.classList.remove('hidden');
                    quantityWrapper.classList.remove('hidden');
                    prices.forEach((item, index) => {
                      const div = document.createElement('div');
                      div.className = 'flex items-center space-x-3 p-4 bg-slate-50 rounded-2xl border-2 border-transparent hover:border-blue-600 hover:bg-white cursor-pointer transition-all group m-0';
                      div.innerHTML = `
                        <input type="radio" name="price" value="${item.price}" id="price-${index}" ${index === 0 ? 'checked' : ''} class="w-5 h-5 text-blue-600 bg-white m-0 border-none shadow-sm cursor-pointer">
                        <label for="price-${index}" class="flex-1 cursor-pointer m-0 pt-0">
                          <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-blue-400 transition-colors leading-tight mb-1">${item.label}</div>
                          <div class="text-sm font-black text-slate-900 font-mono leading-tight">${item.price} ₽</div>
                        </label>
                      `;
                      div.addEventListener('click', () => {
                        div.querySelector('input').checked = true;
                        updateTotal();
                      });
                      priceList.appendChild(div);
                    });
                    updateTotal();
                  } else {
                    priceVariants.classList.add('hidden');
                    quantityWrapper.classList.add('hidden');
                  }
                });
            }

            // Quantity stepper
            function getQty() {
              return Math.min(50, Math.max(1, parseInt(qtyInput.value) || 1));
            }

            function updateTotal() {
              const checkedPrice = priceList.querySelector('input[name="price"]:checked');
              const qty = getQty();
              if (checkedPrice && qtyTotalDisplay) {
                const total = (parseFloat(checkedPrice.value) * qty).toLocaleString('ru-RU');
                qtyTotalDisplay.textContent = total + ' ₽';
              }
            }

            if (qtyDown) qtyDown.addEventListener('click', () => { qtyInput.value = Math.max(1, getQty() - 1); updateTotal(); });
            if (qtyUp) qtyUp.addEventListener('click', () => { qtyInput.value = Math.min(50, getQty() + 1); updateTotal(); });
            if (qtyInput) qtyInput.addEventListener('input', () => { qtyInput.value = Math.min(50, Math.max(1, parseInt(qtyInput.value) || 1)); updateTotal(); });


            if(form) {
                form.addEventListener('submit', async (e) => {
                  e.preventDefault();
                  const formData = new FormData(form);
                  formData.append('action', 'create_personalized_offer');
                  formData.append('nonce', '<?php echo wp_create_nonce("manager_dashboard_nonce"); ?>');

                  const submitBtn = form.querySelector('button[type="submit"]');
                  const originalText = submitBtn.innerText;
                  submitBtn.innerText = 'Генерация...';
                  submitBtn.disabled = true;
                  submitBtn.style.opacity = '0.7';

                  try {
                    const response = await fetch(ajaxurl, {
                      method: 'POST',
                      body: formData
                    });
                    const data = await response.json();
                    
                    if (data.success) {
                      resultDiv.classList.remove('hidden');
                      generatedLink.value = data.data.link;
                      // resultDiv.scrollIntoView({ behavior: 'smooth' }); // Unnecessary for admin UI
                    } else {
                      alert('Ошибка: ' + (data.data.message || 'Неизвестная ошибка'));
                    }
                  } catch (err) {
                    console.error(err);
                    alert('Ошибка сети.');
                  } finally {
                    submitBtn.innerText = originalText;
                    submitBtn.disabled = false;
                    submitBtn.style.opacity = '1';
                  }
                });
            }

            function copyLink(e) {
              e.preventDefault();
              generatedLink.select();
              document.execCommand('copy');
              const btn = e.target;
              const originalText = btn.innerText;
              btn.innerText = 'Готово!';
              btn.classList.add('bg-slate-900', 'text-white');
              btn.classList.remove('bg-white', 'text-blue-600');
              setTimeout(() => {
                btn.innerText = originalText;
                btn.classList.remove('bg-slate-900', 'text-white');
                btn.classList.add('bg-white', 'text-blue-600');
              }, 2000);
            }
        </script>
    </div>
    <?php
}

// 5. Global Admin Cleanup & UI Overhaul for Offer Managers
add_action('admin_enqueue_scripts', function () {
    $user = wp_get_current_user();
    if (!in_array('offer_manager', $user->roles)) return;

    $css_url = content_url('mu-plugins/order/offer-manager-assets/admin-style.css');
    wp_enqueue_style('om-admin-style', $css_url, [], filemtime(__DIR__ . '/offer-manager-assets/admin-style.css'));
});

// 6. Bottom Sidebar UI & Abstract Background State
add_action('admin_footer', function() {
    $user = wp_get_current_user();
    if (!in_array('offer_manager', $user->roles)) return;
    
    $name = esc_html($user->display_name);
    $logout_url = wp_logout_url(home_url());
    $bg_pref = get_user_meta($user->ID, 'om_admin_bg', true) ?: 'abstract';

    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            const menu = document.getElementById('adminmenu');
            if (menu) {
                const bottomHTML = `
                    <li class='om-user-wrapper menu-top'>
                        <div class='om-user-name'>{$name}</div>
                    </li>
                    <li class='menu-top menu-top-last om-custom-menu-item'>
                        <a href='#' id='om-toggle-bg' class='wp-has-submenu wp-not-current-submenu menu-top'>
                            <div class='wp-menu-arrow'></div>
                            <div class='wp-menu-image dashicons-before dashicons-art'><br></div>
                            <div class='wp-menu-name'>Стиль фона</div>
                        </a>
                    </li>
                    <li class='menu-top menu-top-last om-custom-menu-item'>
                        <a href='{$logout_url}' class='wp-has-submenu wp-not-current-submenu menu-top om-logout-link'>
                            <div class='wp-menu-arrow'></div>
                            <div class='wp-menu-image dashicons-before dashicons-exit'><br></div>
                            <div class='wp-menu-name'>Выйти</div>
                        </a>
                    </li>
                `;
                const collapseMenu = document.getElementById('collapse-menu');
                if (collapseMenu) {
                    collapseMenu.insertAdjacentHTML('beforebegin', bottomHTML);
                } else {
                    menu.insertAdjacentHTML('beforeend', bottomHTML);
                }
                
                if ('{$bg_pref}' === 'abstract') {
                    document.body.classList.add('om-bg-abstract');
                } else {
                    document.body.classList.add('om-bg-solid');
                }
                
                document.getElementById('om-toggle-bg').addEventListener('click', function(e) {
                    e.preventDefault();
                    const body = document.body;
                    let newState = 'solid';
                    if (body.classList.contains('om-bg-solid')) {
                        body.classList.replace('om-bg-solid', 'om-bg-abstract');
                        newState = 'abstract';
                    } else {
                        body.classList.replace('om-bg-abstract', 'om-bg-solid');
                    }
                    
                    const formData = new FormData();
                    formData.append('action', 'om_save_bg_pref');
                    formData.append('state', newState);
                    formData.append('nonce', '" . wp_create_nonce('om_bg_nonce') . "');
                    
                    fetch(ajaxurl, {
                        method: 'POST',
                        body: formData
                    });
                });
            }
            // Fix WordPress pushing html down 32px on desktop only via css, not JS
        });
    </script>";
});

// 7. Save Background Preference via AJAX
add_action('wp_ajax_om_save_bg_pref', function() {
    if (!check_ajax_referer('om_bg_nonce', 'nonce', false)) wp_send_json_error();
    $state = $_POST['state'] === 'abstract' ? 'abstract' : 'solid';
    update_user_meta(get_current_user_id(), 'om_admin_bg', $state);
    wp_send_json_success();
});

// 8. Custom Admin Footer for Offer Manager
add_filter('admin_footer_text', function($text) {
    $user = wp_get_current_user();
    if (in_array('offer_manager', $user->roles)) {
        return '<span id="footer-thankyou">E-diet Manager Panel</span>';
    }
    return $text;
});

add_filter('update_footer', function($text) {
    $user = wp_get_current_user();
    if (in_array('offer_manager', $user->roles)) {
        return 'Version 1.0.37';
    }
    return $text;
}, 99);
