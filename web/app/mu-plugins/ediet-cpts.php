<?php
/**
 * Plugin Name: e-Diet Custom Post Types
 * Description: Registers core CPTs for the application: Clinics, Laboratories, Doctors, Diseases, Services, Courses, Products, Reviews, Orders.
 */

add_action('init', 'ediet_register_cpts', 0);

function ediet_register_cpts() {
    // 1. Clinics
    register_post_type('clinic', [
        'labels' => ['name' => __('Клиники', 'ediet'), 'singular_name' => __('Клиника', 'ediet')],
        'public' => true, 'has_archive' => true, 'show_in_rest' => true,
        'menu_icon' => 'dashicons-building',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'rewrite' => ['slug' => 'clinics', 'with_front' => false]
    ]);

    // 2. Laboratories
    register_post_type('laboratory', [
        'labels' => ['name' => __('Лаборатории', 'ediet'), 'singular_name' => __('Лаборатория', 'ediet')],
        'public' => true, 'has_archive' => true, 'show_in_rest' => true,
        'menu_icon' => 'dashicons-test',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'rewrite' => ['slug' => 'laboratories', 'with_front' => false]
    ]);

    // 3. Doctors / Specialists
    register_post_type('doctor', [
        'labels' => ['name' => __('Специалисты', 'ediet'), 'singular_name' => __('Специалист', 'ediet')],
        'public' => true, 'has_archive' => true, 'show_in_rest' => true,
        'menu_icon' => 'dashicons-businessman',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'rewrite' => ['slug' => 'doctors', 'with_front' => false]
    ]);

    // 4. Disease (Диагноз)
    register_post_type('disease', [
        'labels' => ['name' => __('Диагнозы', 'ediet'), 'singular_name' => __('Диагноз', 'ediet')],
        'public' => true, 'has_archive' => false, 'show_in_rest' => true,
        'menu_icon' => 'dashicons-heart',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'rewrite' => ['slug' => 'disease', 'with_front' => false]
    ]);

    register_taxonomy('disease_category', ['disease', 'consultation', 'course', 'book'], [
        'labels' => ['name' => __('Категории диагнозов', 'ediet')],
        'hierarchical' => true, 'show_in_rest' => true,
    ]);

    // 5. Consultation (Консультация)
    register_post_type('consultation', [
        'labels' => ['name' => __('Консультации', 'ediet'), 'singular_name' => __('Консультация', 'ediet')],
        'public' => true, 'has_archive' => true, 'show_in_rest' => true,
        'menu_icon' => 'dashicons-clipboard',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'rewrite' => ['slug' => 'consultations', 'with_front' => false]
    ]);

    // 6. Course (Курс)
    register_post_type('course', [
        'labels' => ['name' => __('Курсы', 'ediet'), 'singular_name' => __('Курс', 'ediet')],
        'public' => true, 'has_archive' => true, 'show_in_rest' => true,
        'menu_icon' => 'dashicons-welcome-learn-more',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'rewrite' => ['slug' => 'course', 'with_front' => false]
    ]);

    // 7. Book (Книга - formerly MPO/Лендинг)
    register_post_type('book', [
        'labels' => ['name' => __('Книги', 'ediet'), 'singular_name' => __('Книга', 'ediet')],
        'public' => true, 'has_archive' => true, 'show_in_rest' => true,
        'menu_icon' => 'dashicons-book',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'rewrite' => ['slug' => 'books', 'with_front' => false]
    ]);

    // 8. Review (Отзывы)
    register_post_type('review', [
        'labels' => ['name' => __('Отзывы', 'ediet'), 'singular_name' => __('Отзыв', 'ediet')],
        'public' => true, 'has_archive' => false, 'show_in_rest' => true,
        'menu_icon' => 'dashicons-star-filled',
        'supports' => ['title', 'editor', 'thumbnail'],
    ]);

    // 9. Orders (Кастомные заказы)
    register_post_type('ediet_order', [
        'labels' => ['name' => __('Заказы', 'ediet'), 'singular_name' => __('Заказ', 'ediet')],
        'public' => false, 'show_ui' => true, 'show_in_rest' => true,
        'menu_icon' => 'dashicons-cart', // Reusing cart icon
        'supports' => ['title', 'custom-fields'],
    ]);
}
