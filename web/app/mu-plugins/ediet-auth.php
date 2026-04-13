<?php
/**
 * Plugin Name: e-Diet Passwordless Authentication
 * Description: Handles OTP login via email without passwords.
 */

add_action('wp_ajax_ediet_send_otp', 'ediet_send_otp_handler');
add_action('wp_ajax_nopriv_ediet_send_otp', 'ediet_send_otp_handler');

// 1. Send OTP Endpoint
function ediet_send_otp_handler() {
    check_ajax_referer('ediet_auth_nonce', 'nonce');
    
    $email = sanitize_email($_POST['email'] ?? '');
    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Некорректный email.']);
    }

    $user = get_user_by('email', $email);
    if (!$user) {
        wp_send_json_error(['message' => 'Аккаунт с таким email не найден. Оформите заказ для создания аккаунта.']);
    }

    $code = wp_rand(100000, 999999);
    
    update_user_meta($user->ID, '_ediet_otp_code', wp_hash($code));
    update_user_meta($user->ID, '_ediet_otp_expires', time() + 600); // 10 minutes

    // Send Email
    $subject = 'Ваш код для входа в Личный Кабинет';
    $message = "Код для входа: <b>{$code}</b>\nКод действителен в течение 10 минут.";
    $headers = ['Content-Type: text/html; charset=UTF-8'];

    wp_mail($email, $subject, $message, $headers);

    wp_send_json_success(['message' => 'Код отправлен на вашу почту.']);
}

add_action('wp_ajax_ediet_verify_otp', 'ediet_verify_otp_handler');
add_action('wp_ajax_nopriv_ediet_verify_otp', 'ediet_verify_otp_handler');

// 2. Verify OTP Endpoint
function ediet_verify_otp_handler() {
    check_ajax_referer('ediet_auth_nonce', 'nonce');

    $email = sanitize_email($_POST['email'] ?? '');
    $code  = sanitize_text_field($_POST['code'] ?? '');

    if (!$email || !$code) {
        wp_send_json_error(['message' => 'Заполните все поля.']);
    }

    $user = get_user_by('email', $email);
    if (!$user) {
        wp_send_json_error(['message' => 'Ошибка входа.']);
    }

    $saved_hash = get_user_meta($user->ID, '_ediet_otp_code', true);
    $expires    = get_user_meta($user->ID, '_ediet_otp_expires', true);

    if (time() > (int)$expires) {
        wp_send_json_error(['message' => 'Срок действия кода истек. Запросите новый.']);
    }

    if (wp_hash($code) !== $saved_hash) {
        wp_send_json_error(['message' => 'Неверный код.']);
    }

    // Success! Clear meta and login.
    delete_user_meta($user->ID, '_ediet_otp_code');
    delete_user_meta($user->ID, '_ediet_otp_expires');

    wp_clear_auth_cookie();
    wp_set_current_user($user->ID);
    wp_set_auth_cookie($user->ID, true); // Keep logged in

    wp_send_json_success(['message' => 'Успешный вход.', 'redirect' => wc_get_account_endpoint_url('dashboard')]);
}

// 3. Shortcode to display Form
add_shortcode('ediet_otp_login', function() {
    if (is_user_logged_in()) {
        return '<p>Вы уже вошли в систему. <a href="'.wc_get_account_endpoint_url('dashboard').'">Перейти в ЛК</a></p>';
    }

    wp_enqueue_script('jquery');

    $nonce = wp_create_nonce('ediet_auth_nonce');
    
    ob_start();
    ?>
    <div id="ediet-otp-container" style="max-width:400px; padding:20px; border:1px solid #ddd; border-radius:12px; font-family:sans-serif;">
        <h3 style="margin-top:0;">Вход в Личный кабинет</h3>
        
        <form id="ediet-otp-email-form">
            <p>Введите ваш Email, чтобы получить код доступа:</p>
            <input type="email" id="ediet-otp-email" required placeholder="Ваш Email" style="width:100%; padding:12px; margin-bottom:10px; border:1px solid #ccc; border-radius:6px;">
            <button type="submit" style="width:100%; padding:12px; background:#111; color:#fff; border:none; border-radius:6px; cursor:pointer;">Отправить код</button>
        </form>

        <form id="ediet-otp-code-form" style="display:none;">
            <p>Код отправлен на почту. Введите его ниже:</p>
            <input type="text" id="ediet-otp-code" required placeholder="Код (6 цифр)" maxlength="6" style="width:100%; padding:12px; margin-bottom:10px; border:1px solid #ccc; border-radius:6px; letter-spacing:4px; font-weight:bold; text-align:center;">
            <button type="submit" style="width:100%; padding:12px; background:#111; color:#fff; border:none; border-radius:6px; cursor:pointer;">Войти</button>
        </form>
        
        <div id="ediet-otp-message" style="margin-top:15px; color:#d32f2f; font-size:14px; text-align:center;"></div>
    </div>
    <script>
    jQuery(document).ready(function($) {
        var userEmail = '';

        $('#ediet-otp-email-form').on('submit', function(e) {
            e.preventDefault();
            userEmail = $('#ediet-otp-email').val();
            var btn = $(this).find('button');
            btn.text('Отправка...').prop('disabled', true);
            
            $.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                action: 'ediet_send_otp',
                nonce: '<?php echo $nonce; ?>',
                email: userEmail
            }, function(res) {
                btn.text('Отправить код').prop('disabled', false);
                if(res.success) {
                    $('#ediet-otp-email-form').hide();
                    $('#ediet-otp-code-form').show();
                    $('#ediet-otp-message').css('color', 'green').text(res.data.message);
                } else {
                    $('#ediet-otp-message').css('color', 'red').text(res.data.message);
                }
            });
        });

        $('#ediet-otp-code-form').on('submit', function(e) {
            e.preventDefault();
            var btn = $(this).find('button');
            btn.text('Вход...').prop('disabled', true);
            
            $.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                action: 'ediet_verify_otp',
                nonce: '<?php echo $nonce; ?>',
                email: userEmail,
                code: $('#ediet-otp-code').val()
            }, function(res) {
                if(res.success) {
                    $('#ediet-otp-message').css('color', 'green').text(res.data.message);
                    window.location.href = res.data.redirect;
                } else {
                    btn.text('Войти').prop('disabled', false);
                    $('#ediet-otp-message').css('color', 'red').text(res.data.message);
                }
            });
        });
    });
    </script>
    <?php
    return ob_get_clean();
});
