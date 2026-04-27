<?php
/**
 * Plugin Name: E-Diet Seats Manager
 * Description: Manages available seats per course: decrements on purchase, resets periodically.
 * Version: 1.0.0
 * Author: E-Diet
 */

if (!defined('ABSPATH')) exit;

class EdietSeatsManager
{
    const CRON_HOOK   = 'ediet_seats_reset_check';
    const AJAX_ACTION = 'ediet_reset_course_seats';

    public function __construct()
    {
        add_action('transition_post_status', [$this, 'on_offer_paid'], 10, 3);

        // WP Cron
        add_action(self::CRON_HOOK, [$this, 'run_periodic_resets']);
        register_activation_hook(__FILE__, [$this, 'schedule_cron']);
        register_deactivation_hook(__FILE__, [$this, 'unschedule_cron']);
        add_action('init', [$this, 'maybe_schedule_cron']);

        // Admin list column
        add_filter('manage_course_posts_columns',       [$this, 'add_seats_column']);
        add_action('manage_course_posts_custom_column', [$this, 'render_seats_column'], 10, 2);

        // Admin AJAX reset
        add_action('wp_ajax_' . self::AJAX_ACTION, [$this, 'ajax_reset_seats']);

        // Enqueue inline JS for the reset button in admin
        add_action('admin_footer-edit.php', [$this, 'admin_footer_js']);
    }

    // -------------------------------------------------------------------------
    // Decrement on purchase
    // -------------------------------------------------------------------------

    public function on_offer_paid($new_status, $old_status, $post)
    {
        if ($new_status === $old_status)    return;
        if ($new_status !== 'paid')         return;
        if ($post->post_type !== 'personal_offer') return;

        $product_id = (int) get_post_meta($post->ID, 'target_product', true);
        if (!$product_id) return;

        if (get_post_type($product_id) !== 'course') return;

        $this->decrement($product_id);
    }

    public function decrement($course_id)
    {
        $enabled = (bool) get_field('ps_seats_enabled', $course_id);
        if (!$enabled) return;

        $current = (int) get_post_meta($course_id, '_seats_current', true);
        if ($current <= 0) return;

        update_post_meta($course_id, '_seats_current', $current - 1);
    }

    // -------------------------------------------------------------------------
    // Periodic reset via WP Cron (runs hourly)
    // -------------------------------------------------------------------------

    public function schedule_cron()
    {
        if (!wp_next_scheduled(self::CRON_HOOK)) {
            wp_schedule_event(time(), 'hourly', self::CRON_HOOK);
        }
    }

    public function unschedule_cron()
    {
        $ts = wp_next_scheduled(self::CRON_HOOK);
        if ($ts) wp_unschedule_event($ts, self::CRON_HOOK);
    }

    public function maybe_schedule_cron()
    {
        if (!wp_next_scheduled(self::CRON_HOOK)) {
            wp_schedule_event(time(), 'hourly', self::CRON_HOOK);
        }
    }

    public function run_periodic_resets()
    {
        $courses = get_posts([
            'post_type'      => 'course',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'meta_query'     => [
                [
                    'key'     => 'ps_seats_enabled',
                    'value'   => '1',
                    'compare' => '=',
                ],
            ],
            'fields' => 'ids',
        ]);

        $now = time();

        foreach ($courses as $course_id) {
            $base   = (int) get_field('ps_seats_base', $course_id);
            $period = get_field('ps_seats_reset_period', $course_id) ?: 'never';

            if ($period === 'never' || $base <= 0) continue;

            $next_reset = (int) get_post_meta($course_id, '_seats_reset_next', true);

            if ($next_reset && $now < $next_reset) continue;

            // Perform reset
            update_post_meta($course_id, '_seats_current', $base);
            update_post_meta($course_id, '_seats_reset_next', $this->next_reset_timestamp($period, $now));
        }
    }

    private function next_reset_timestamp($period, $from)
    {
        switch ($period) {
            case 'daily':     return $from + DAY_IN_SECONDS;
            case 'weekly':    return $from + WEEK_IN_SECONDS;
            case 'biweekly':  return $from + 2 * WEEK_IN_SECONDS;
            case 'monthly':   return $from + 30 * DAY_IN_SECONDS;
            default:          return 0;
        }
    }

    // -------------------------------------------------------------------------
    // Admin column
    // -------------------------------------------------------------------------

    public function add_seats_column($columns)
    {
        $columns['seats'] = 'Места';
        return $columns;
    }

    public function render_seats_column($column, $post_id)
    {
        if ($column !== 'seats') return;

        $enabled = (bool) get_field('ps_seats_enabled', $post_id);
        if (!$enabled) {
            echo '<span style="color:#999">—</span>';
            return;
        }

        $base    = (int) get_field('ps_seats_base', $post_id);
        $current = (int) get_post_meta($post_id, '_seats_current', true);
        $nonce   = wp_create_nonce('ediet_reset_seats_' . $post_id);

        printf(
            '<strong>%d</strong> / %d &nbsp;
            <a href="#" style="font-size:11px;color:#d63638;"
               data-id="%d" data-nonce="%s"
               class="ediet-reset-seats">↺ сброс</a>',
            $current,
            $base,
            $post_id,
            $nonce
        );
    }

    // -------------------------------------------------------------------------
    // AJAX: manual reset
    // -------------------------------------------------------------------------

    public function ajax_reset_seats()
    {
        $course_id = (int) ($_POST['course_id'] ?? 0);
        $nonce     = sanitize_text_field($_POST['nonce'] ?? '');

        if (!$course_id || !wp_verify_nonce($nonce, 'ediet_reset_seats_' . $course_id)) {
            wp_send_json_error(['message' => 'Недействительный запрос'], 403);
        }

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => 'Недостаточно прав'], 403);
        }

        $base = (int) get_field('ps_seats_base', $course_id);
        update_post_meta($course_id, '_seats_current', $base);

        wp_send_json_success(['current' => $base]);
    }

    // -------------------------------------------------------------------------
    // Inline JS for the admin reset button
    // -------------------------------------------------------------------------

    public function admin_footer_js()
    {
        global $typenow;
        if ($typenow !== 'course') return;
        ?>
        <script>
        document.querySelectorAll('.ediet-reset-seats').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                if (!confirm('Сбросить счётчик мест к базовому значению?')) return;
                var id    = this.dataset.id;
                var nonce = this.dataset.nonce;
                var el    = this;
                fetch(ajaxurl, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'action=ediet_reset_course_seats&course_id=' + id + '&nonce=' + nonce
                })
                .then(function(r){ return r.json(); })
                .then(function(data) {
                    if (data.success) {
                        var cell = el.closest('td');
                        var strong = cell.querySelector('strong');
                        if (strong) strong.textContent = data.data.current;
                    } else {
                        alert(data.data.message || 'Ошибка');
                    }
                });
            });
        });
        </script>
        <?php
    }
}

new EdietSeatsManager();
