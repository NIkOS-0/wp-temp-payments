<?php
/**
 * Plugin Name: e-Diet Custom Post Types
 * Description: Registers core CPTs for the application: Clinics, Laboratories, Doctors, and Products.
 */

add_action('init', 'ediet_register_cpts', 0);

function ediet_register_cpts() {
    // 1. Clinics
    register_post_type('clinic', [
        'labels' => [
            'name'               => __('Клиники', 'ediet'),
            'singular_name'      => __('Клиника', 'ediet'),
            'add_new_item'       => __('Добавить клинику', 'ediet'),
            'edit_item'          => __('Редактировать клинику', 'ediet'),
            'search_items'       => __('Найти клинику', 'ediet'),
        ],
        'public'              => true,
        'has_archive'         => true,
        'hierarchical'        => false,
        'show_in_rest'        => true,
        'rest_base'           => 'clinics',
        'menu_icon'           => 'dashicons-building',
        'supports'            => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'rewrite'             => ['slug' => 'clinics', 'with_front' => false],
    ]);

    // 2. Laboratories
    register_post_type('laboratory', [
        'labels' => [
            'name'               => __('Лаборатории', 'ediet'),
            'singular_name'      => __('Лаборатория', 'ediet'),
            'add_new_item'       => __('Добавить лабораторию', 'ediet'),
            'edit_item'          => __('Редактировать лабораторию', 'ediet'),
            'search_items'       => __('Найти лабораторию', 'ediet'),
        ],
        'public'              => true,
        'has_archive'         => true,
        'hierarchical'        => false,
        'show_in_rest'        => true,
        'rest_base'           => 'laboratories',
        'menu_icon'           => 'dashicons-test',
        'supports'            => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'rewrite'             => ['slug' => 'laboratories', 'with_front' => false],
    ]);

    // 3. Doctors / Specialists
    register_post_type('doctor', [
        'labels' => [
            'name'               => __('Специалисты', 'ediet'),
            'singular_name'      => __('Специалист', 'ediet'),
            'add_new_item'       => __('Добавить специалиста', 'ediet'),
            'edit_item'          => __('Редактировать специалиста', 'ediet'),
            'search_items'       => __('Найти специалиста', 'ediet'),
        ],
        'public'              => true,
        'has_archive'         => true,
        'hierarchical'        => false,
        'show_in_rest'        => true,
        'rest_base'           => 'doctors',
        'menu_icon'           => 'dashicons-businessman',
        'supports'            => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'rewrite'             => ['slug' => 'doctors', 'with_front' => false],
    ]);

    // 4. Disease (Диагноз)
    register_post_type('disease', [
        'labels' => [
            'name'               => __('Диагнозы', 'ediet'),
            'singular_name'      => __('Диагноз', 'ediet'),
            'add_new_item'       => __('Добавить диагноз', 'ediet'),
            'edit_item'          => __('Редактировать диагноз', 'ediet'),
        ],
        'public'              => true,
        'has_archive'         => false,
        'show_in_rest'        => true,
        'rest_base'           => 'diseases',
        'menu_icon'           => 'dashicons-heart',
        'supports'            => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'rewrite'             => ['slug' => 'disease', 'with_front' => false],
    ]);

    register_taxonomy('disease_category', ['disease'], [
        'labels'            => [
            'name'          => __('Категории диагнозов', 'ediet'),
            'singular_name' => __('Категория диагнозов', 'ediet'),
        ],
        'hierarchical'      => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'diseases'],
    ]);

    // 5. Service (Услуга)
    register_post_type('service', [
        'labels' => [
            'name'               => __('Услуги', 'ediet'),
            'singular_name'      => __('Услуга', 'ediet'),
        ],
        'public'              => true,
        'has_archive'         => true,
        'show_in_rest'        => true,
        'menu_icon'           => 'dashicons-clipboard',
        'supports'            => ['title', 'editor', 'thumbnail', 'excerpt'],
        'rewrite'             => ['slug' => 'service', 'with_front' => false],
    ]);

    // 6. Course (Курс)
    register_post_type('course', [
        'labels' => [
            'name'               => __('Курсы', 'ediet'),
            'singular_name'      => __('Курс', 'ediet'),
        ],
        'public'              => true,
        'has_archive'         => true,
        'show_in_rest'        => true,
        'menu_icon'           => 'dashicons-welcome-learn-more',
        'supports'            => ['title', 'editor', 'thumbnail', 'excerpt'],
        'rewrite'             => ['slug' => 'course', 'with_front' => false],
    ]);

    // 7. Review (Отзывы)
    register_post_type('review', [
        'labels' => [
            'name'               => __('Отзывы', 'ediet'),
            'singular_name'      => __('Отзыв', 'ediet'),
        ],
        'public'              => true,
        'has_archive'         => false,
        'show_in_rest'        => true,
        'menu_icon'           => 'dashicons-star-filled',
        'supports'            => ['title', 'editor', 'thumbnail'],
        'rewrite'             => ['slug' => 'review', 'with_front' => false],
    ]);
}
