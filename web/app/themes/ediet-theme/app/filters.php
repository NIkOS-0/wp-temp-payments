<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

/**
 * Populate the footer-column "menu" select with all registered WP nav menus.
 * The select stores the menu slug; the template resolves it via wp_nav_menu([ 'menu' => $slug ]).
 */
// ── Archive: 20 items per page for book / course / consultation ───────────
add_action('pre_get_posts', function ($query) {
    if (is_admin() || ! $query->is_main_query()) {
        return;
    }
    if ($query->is_post_type_archive(['book', 'course', 'consultation'])) {
        $query->set('posts_per_page', 20);
    }
}, 5);

// ── Include custom post types in frontend search ──────────────────────────
add_action('pre_get_posts', function ($query) {
    if (is_admin() || ! $query->is_main_query() || ! $query->is_search()) {
        return;
    }

    // Honour explicit post_type[] from the header form; otherwise search all three types
    $requested = array_filter(
        (array) ($_GET['post_type'] ?? []),
        fn($t) => in_array($t, ['book', 'course', 'consultation'], true)
    );

    $query->set('post_type', ! empty($requested)
        ? array_values($requested)
        : ['book', 'course', 'consultation']
    );
});

// ── Live search AJAX endpoint ─────────────────────────────────────────────
add_action('wp_ajax_ediet_search',        __NAMESPACE__ . '\ediet_live_search_handler');
add_action('wp_ajax_nopriv_ediet_search', __NAMESPACE__ . '\ediet_live_search_handler');

function ediet_live_search_handler(): void
{
    $q = sanitize_text_field($_GET['q'] ?? '');

    if (mb_strlen($q) < 2) {
        wp_send_json([]);
    }

    $wpQuery = new \WP_Query([
        'post_type'      => ['book', 'course', 'consultation'],
        'post_status'    => 'publish',
        's'              => $q,
        'posts_per_page' => 6,
        'orderby'        => 'relevance',
        'no_found_rows'  => true,
    ]);

    $typeLabels = ['book' => 'МПО', 'course' => 'Курс', 'consultation' => 'Консультация'];
    $results    = [];

    foreach ($wpQuery->posts as $post) {
        $price = function_exists('get_field') ? get_field('price', $post->ID) : 0;
        $price = (float) str_replace([' ', ','], ['', '.'], (string) $price);

        $results[] = [
            'title' => get_the_title($post->ID),
            'url'   => get_permalink($post->ID),
            'type'  => $post->post_type,
            'label' => $typeLabels[$post->post_type] ?? '',
            'price' => $price > 0 ? number_format($price, 0, '.', ' ') . ' ₽' : '',
            'image' => get_the_post_thumbnail_url($post->ID, 'thumbnail') ?: '',
        ];
    }

    wp_send_json($results);
}

// ── Infinite-scroll AJAX endpoint for archives ────────────────────────────
add_action('wp_ajax_ediet_archive_load',        __NAMESPACE__ . '\ediet_archive_load_handler');
add_action('wp_ajax_nopriv_ediet_archive_load', __NAMESPACE__ . '\ediet_archive_load_handler');

function ediet_archive_load_handler(): void
{
    $post_type = sanitize_key($_GET['post_type'] ?? '');
    if (! in_array($post_type, ['book', 'course', 'consultation'], true)) {
        wp_send_json_error('invalid_type');
    }

    $page = max(1, (int) ($_GET['page'] ?? 1));
    $sort = sanitize_key($_GET['sort'] ?? 'date_desc');

    [$orderby, $order, $meta_key] = match ($sort) {
        'price_asc'  => ['meta_value_num', 'ASC',  'price'],
        'price_desc' => ['meta_value_num', 'DESC', 'price'],
        default      => ['date',           'DESC', null],
    };

    $args = [
        'post_type'      => $post_type,
        'post_status'    => 'publish',
        'posts_per_page' => 20,
        'paged'          => $page,
        'orderby'        => $orderby,
        'order'          => $order,
        'no_found_rows'  => false,
    ];
    if ($meta_key) {
        $args['meta_key'] = $meta_key;
    }

    $q = new \WP_Query($args);

    $type_labels = [
        'book'         => 'Книга',
        'course'       => 'Курс',
        'consultation' => 'Консультация',
    ];

    $field_maps = [
        'book'         => ['features' => 'book_features',   'price_old' => 'book_price_old',   'delivery' => 'book_delivery_note'],
        'course'       => ['features' => 'course_features', 'price_old' => 'course_price_old', 'delivery' => 'course_delivery_note'],
        'consultation' => ['features' => 'benefits',        'price_old' => 'price_old',        'delivery' => 'delivery_note'],
    ];

    $fm    = $field_maps[$post_type];
    $items = [];

    foreach ($q->posts as $post) {
        $features = get_field($fm['features'], $post->ID) ?: [];
        if (empty($features) && $post_type === 'course') {
            $features = get_field('benefits', $post->ID) ?: [];
        }

        $items[] = [
            'id'         => $post->ID,
            'title'      => get_the_title($post->ID),
            'url'        => get_permalink($post->ID),
            'type_label' => $type_labels[$post_type],
            'features'   => array_slice($features, 0, 3),
            'price'      => get_field('price', $post->ID) ?: '',
            'price_old'  => get_field($fm['price_old'], $post->ID) ?: '',
            'delivery'   => get_field($fm['delivery'], $post->ID) ?: '',
            'image'      => get_the_post_thumbnail_url($post->ID, 'medium') ?: '',
            'badge'      => get_field('ps_card_badge', $post->ID) ?: '',
        ];
    }

    wp_send_json([
        'items'    => $items,
        'has_more' => $page < $q->max_num_pages,
    ]);
}

add_filter('acf/load_field/key=field_footer_col_menu', function ($field) {
    $field['choices'] = [];

    if (! function_exists('wp_get_nav_menus')) {
        return $field;
    }

    foreach (wp_get_nav_menus() as $menu) {
        $field['choices'][$menu->slug] = sprintf('%s (#%d)', $menu->name, $menu->term_id);
    }

    return $field;
});
