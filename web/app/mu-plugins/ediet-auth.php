<?php
/**
 * Plugin Name: e-Diet Passwordless Authentication
 * Description: OTP + magic-link login. Debug mode logs codes to browser console.
 */

add_filter('wp_mail_from',      fn() => 'dev@e-diet.wiki');
add_filter('wp_mail_from_name', fn() => 'e-diet');

/* ════════════════════════════════════════
   STEP 1 — Send OTP + magic link
════════════════════════════════════════ */

add_action('wp_ajax_ediet_send_otp',        'ediet_send_otp_handler');
add_action('wp_ajax_nopriv_ediet_send_otp', 'ediet_send_otp_handler');

function ediet_send_otp_handler() {
    check_ajax_referer('ediet_auth_nonce', 'nonce');

    $email    = sanitize_email($_POST['email'] ?? '');
    $redirect = esc_url_raw($_POST['redirect'] ?? home_url('/cabinet'));

    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Некорректный email.']);
    }

    $user = get_user_by('email', $email);

    // In debug mode: auto-create subscriber if not found
    if (!$user && defined('WP_DEBUG') && WP_DEBUG) {
        $user_id = wp_insert_user([
            'user_login' => $email,
            'user_email' => $email,
            'user_pass'  => wp_generate_password(24),
            'role'       => 'subscriber',
        ]);
        if (!is_wp_error($user_id)) {
            $user = get_user_by('ID', $user_id);
        }
    }

    if (!$user) {
        wp_send_json_error(['message' => 'Аккаунт не найден. Оформите первый заказ — аккаунт создастся автоматически.']);
    }

    // Generate 6-digit OTP code
    $code = wp_rand(100000, 999999);
    update_user_meta($user->ID, '_ediet_otp_code',    wp_hash($code));
    update_user_meta($user->ID, '_ediet_otp_expires', time() + 600); // 10 min

    // Generate magic link token
    $magic_token = bin2hex(random_bytes(24));
    update_user_meta($user->ID, '_ediet_magic_token',   wp_hash($magic_token));
    update_user_meta($user->ID, '_ediet_magic_expires', time() + 600);
    update_user_meta($user->ID, '_ediet_magic_redirect', $redirect);

    $magic_url = add_query_arg([
        'ediet_token' => $magic_token,
        'email'       => rawurlencode($email),
    ], home_url('/auth'));

    // Send email with both options
    $subject = 'Войдите в e-diet';
    $message = "
<div style='font-family:sans-serif;max-width:480px;margin:0 auto;padding:32px 24px;background:#fefaf3;border-radius:16px;'>
  <h2 style='font-size:22px;font-weight:800;color:#1c0d00;margin:0 0 8px;'>Войти в e-diet</h2>
  <p style='color:#7a736c;font-size:14px;margin:0 0 28px;'>Вы запросили вход в личный кабинет</p>

  <a href='{$magic_url}' style='display:block;text-align:center;background:#1c0d00;color:#fefaf3;
     padding:16px 24px;border-radius:9999px;font-weight:700;font-size:15px;
     text-decoration:none;margin-bottom:28px;'>
    Войти одним кликом →
  </a>

  <p style='color:#9c7d66;font-size:13px;margin:0 0 8px;'>Или введите код на сайте:</p>
  <div style='font-size:32px;font-weight:900;letter-spacing:8px;color:#1c0d00;
              background:#fff;border:2px solid #e0d0c5;border-radius:12px;
              padding:16px;text-align:center;margin-bottom:24px;'>
    {$code}
  </div>

  <p style='color:#9c7d66;font-size:12px;margin:0;'>Ссылка и код действительны 10 минут.</p>
</div>";

    $headers = ['Content-Type: text/html; charset=UTF-8'];
    wp_mail($email, $subject, $message, $headers);

    $response = ['message' => 'Код и ссылка отправлены на почту.'];

    if (defined('WP_DEBUG') && WP_DEBUG) {
        $response['dev_code']       = $code;
        $response['dev_magic_url']  = $magic_url;
    }

    wp_send_json_success($response);
}

/* ════════════════════════════════════════
   STEP 2 — Verify OTP code
════════════════════════════════════════ */

add_action('wp_ajax_ediet_verify_otp',        'ediet_verify_otp_handler');
add_action('wp_ajax_nopriv_ediet_verify_otp', 'ediet_verify_otp_handler');

function ediet_verify_otp_handler() {
    check_ajax_referer('ediet_auth_nonce', 'nonce');

    $email    = sanitize_email($_POST['email'] ?? '');
    $code     = sanitize_text_field($_POST['code'] ?? '');
    $redirect = esc_url_raw($_POST['redirect'] ?? home_url('/cabinet'));

    if (!$email || !$code) {
        wp_send_json_error(['message' => 'Заполните все поля.']);
    }

    $user = get_user_by('email', $email);
    if (!$user) {
        wp_send_json_error(['message' => 'Ошибка входа.']);
    }

    $saved_hash = get_user_meta($user->ID, '_ediet_otp_code', true);
    $expires    = (int) get_user_meta($user->ID, '_ediet_otp_expires', true);

    if (time() > $expires) {
        wp_send_json_error(['message' => 'Срок действия кода истёк. Запросите новый.']);
    }

    if (wp_hash($code) !== $saved_hash) {
        wp_send_json_error(['message' => 'Неверный код.']);
    }

    // Clear OTP meta
    delete_user_meta($user->ID, '_ediet_otp_code');
    delete_user_meta($user->ID, '_ediet_otp_expires');
    delete_user_meta($user->ID, '_ediet_magic_token');
    delete_user_meta($user->ID, '_ediet_magic_expires');
    delete_user_meta($user->ID, '_ediet_magic_redirect');

    // Set auth cookie — JS will handle the redirect via JSON response
    wp_clear_auth_cookie();
    wp_set_current_user($user->ID);
    wp_set_auth_cookie($user->ID, true);

    ediet_merge_guest_favorites($user->ID);

    // Return JSON — never redirect from AJAX handler
    wp_send_json_success(['message' => 'Добро пожаловать!', 'redirect' => $redirect]);
}

/* ════════════════════════════════════════
   Magic link — token verification via GET
════════════════════════════════════════ */

add_action('template_redirect', 'ediet_handle_magic_link');

function ediet_handle_magic_link() {
    if (is_user_logged_in()) return;

    $token = sanitize_text_field($_GET['ediet_token'] ?? '');
    $email = sanitize_email($_GET['email'] ?? '');

    if (!$token || !$email) return;

    $user = get_user_by('email', $email);
    if (!$user) return;

    $saved_hash = get_user_meta($user->ID, '_ediet_magic_token',   true);
    $expires    = (int) get_user_meta($user->ID, '_ediet_magic_expires', true);
    $redirect   = get_user_meta($user->ID, '_ediet_magic_redirect', true) ?: home_url('/cabinet');

    if (time() > $expires || wp_hash($token) !== $saved_hash) {
        // Expired/invalid — show error on auth page
        wp_safe_redirect(add_query_arg('auth_error', 'link_expired', home_url('/auth')));
        exit;
    }

    ediet_do_login($user, $redirect);
}

/* ════════════════════════════════════════
   Shared login helper
════════════════════════════════════════ */

function ediet_do_login(WP_User $user, string $redirect): never {
    delete_user_meta($user->ID, '_ediet_otp_code');
    delete_user_meta($user->ID, '_ediet_otp_expires');
    delete_user_meta($user->ID, '_ediet_magic_token');
    delete_user_meta($user->ID, '_ediet_magic_expires');
    delete_user_meta($user->ID, '_ediet_magic_redirect');

    wp_clear_auth_cookie();
    wp_set_current_user($user->ID);
    wp_set_auth_cookie($user->ID, true);

    // Merge guest favorites on login
    ediet_merge_guest_favorites($user->ID);

    wp_safe_redirect($redirect);
    exit;
}

/* ════════════════════════════════════════
   Favorites — guest merge on login
════════════════════════════════════════ */

add_action('wp_ajax_ediet_toggle_favorite',        'ediet_toggle_favorite');
add_action('wp_ajax_nopriv_ediet_toggle_favorite', 'ediet_toggle_favorite');

function ediet_toggle_favorite() {
    check_ajax_referer('ediet_auth_nonce', 'nonce');

    $post_id = (int) ($_POST['post_id'] ?? 0);
    if (!$post_id) wp_send_json_error();

    if (!is_user_logged_in()) {
        // Guest: handled client-side via localStorage
        wp_send_json_success(['guest' => true]);
    }

    $user_id   = get_current_user_id();
    $favorites = ediet_get_favorites($user_id);

    if (in_array($post_id, $favorites, true)) {
        $favorites = array_values(array_diff($favorites, [$post_id]));
        $added = false;
    } else {
        $favorites[] = $post_id;
        $added = true;
    }

    update_user_meta($user_id, '_ediet_favorites', $favorites);
    wp_send_json_success(['added' => $added, 'count' => count($favorites)]);
}

add_action('wp_ajax_ediet_get_favorites', 'ediet_get_favorites_handler');
add_action('wp_ajax_nopriv_ediet_get_favorites', 'ediet_get_favorites_handler');

function ediet_get_favorites_handler() {
    check_ajax_referer('ediet_auth_nonce', 'nonce');
    if (!is_user_logged_in()) wp_send_json_success(['ids' => []]);
    wp_send_json_success(['ids' => ediet_get_favorites(get_current_user_id())]);
}

function ediet_get_favorites(int $user_id): array {
    $val = get_user_meta($user_id, '_ediet_favorites', true);
    return is_array($val) ? array_map('intval', $val) : [];
}

function ediet_merge_guest_favorites(int $user_id): void {
    // Guest favorites are passed in cookie `ediet_guest_favorites` as JSON
    $raw = $_COOKIE['ediet_guest_favorites'] ?? '';
    if (!$raw) return;

    $guest_ids = json_decode(stripslashes($raw), true);
    if (!is_array($guest_ids)) return;

    $existing = ediet_get_favorites($user_id);
    $merged   = array_values(array_unique(array_merge($existing, array_map('intval', $guest_ids))));
    update_user_meta($user_id, '_ediet_favorites', $merged);
}

/* ════════════════════════════════════════
   Logout
════════════════════════════════════════ */

add_action('wp_ajax_ediet_logout', function() {
    check_ajax_referer('ediet_auth_nonce', 'nonce');
    wp_logout();
    wp_send_json_success(['redirect' => home_url('/')]);
});

/* ════════════════════════════════════════
   Enqueue shared auth nonce for JS
════════════════════════════════════════ */

add_action('wp_head', function() {
    $auth_data = [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('ediet_auth_nonce'),
        'isLoggedIn' => is_user_logged_in(),
        'cabinetUrl' => home_url('/cabinet'),
        'authUrl'    => home_url('/auth'),
        'debug'      => defined('WP_DEBUG') && WP_DEBUG,
    ];
    echo '<script>window.edietAuth = ' . wp_json_encode($auth_data) . ';</script>';
}, 5);

/* ════════════════════════════════════════
   Profile update
════════════════════════════════════════ */

add_action('wp_ajax_ediet_update_profile', function() {
    check_ajax_referer('ediet_auth_nonce', 'nonce');
    if (!is_user_logged_in()) wp_send_json_error(['message' => 'Не авторизован.']);

    $user_id    = get_current_user_id();
    $first_name = sanitize_text_field($_POST['first_name'] ?? '');
    $last_name  = sanitize_text_field($_POST['last_name'] ?? '');

    wp_update_user([
        'ID'           => $user_id,
        'first_name'   => $first_name,
        'last_name'    => $last_name,
        'display_name' => trim("$first_name $last_name") ?: get_userdata($user_id)->user_login,
    ]);

    wp_send_json_success(['message' => 'Профиль обновлён.']);
});
