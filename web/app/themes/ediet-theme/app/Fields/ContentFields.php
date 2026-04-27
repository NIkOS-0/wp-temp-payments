<?php

namespace App\Fields;

/**
 * ACF field groups for Consultation, Course, and Doctor CPTs.
 *
 * Field-name conventions
 * ──────────────────────
 *  ps_*           shared "product settings" used by both consultation & course
 *  benefits       shared feature/benefit list
 *  price          current price (text to support ranges like "от 5 000")
 *  consultation_* consultation-specific
 *  course_*       course-specific
 *  doc_*          doctor / specialist fields
 */

add_action('acf/init', function () {

    /* ═══════════════════════════════════════════════════════
       1.  SHARED — Consultation & Course product settings
    ═══════════════════════════════════════════════════════ */
    acf_add_local_field_group([
        'key'    => 'group_ps_shared',
        'title'  => 'Настройки продукта (консультация / курс)',
        'fields' => [

            /* ── Hero labels ── */
            [
                'key'          => 'field_ps_top_labels',
                'label'        => 'Лейблы (теги над заголовком)',
                'name'         => 'ps_top_labels',
                'type'         => 'repeater',
                'layout'       => 'table',
                'button_label' => 'Добавить лейбл',
                'sub_fields'   => [
                    [
                        'key'     => 'field_ps_label_text',
                        'label'   => 'Текст',
                        'name'    => 'text',
                        'type'    => 'text',
                        'wrapper' => ['width' => '40'],
                    ],
                    [
                        'key'           => 'field_ps_label_bg',
                        'label'         => 'Фон (HEX)',
                        'name'          => 'bg_color',
                        'type'          => 'color_picker',
                        'instructions'  => 'Например, #FDE8D4',
                        'default_value' => '',
                        'wrapper'       => ['width' => '20'],
                    ],
                    [
                        'key'           => 'field_ps_label_fg',
                        'label'         => 'Текст (HEX)',
                        'name'          => 'text_color',
                        'type'          => 'color_picker',
                        'instructions'  => 'Например, #B85E30',
                        'default_value' => '',
                        'wrapper'       => ['width' => '20'],
                    ],
                    [
                        'key'           => 'field_ps_label_style',
                        'label'         => 'Пресет (если HEX пустой)',
                        'name'          => 'style',
                        'type'          => 'select',
                        'choices'       => [
                            'default'  => 'Тёплый',
                            'verified' => 'Зелёный',
                            'pdf'      => 'Оранжевый',
                            'dark'     => 'Тёмный',
                        ],
                        'default_value' => 'default',
                        'wrapper'       => ['width' => '20'],
                    ],
                ],
            ],

            /* ── Card badge ── */
            [
                'key'          => 'field_ps_card_badge',
                'label'        => 'Бейдж на карточке (напр. «Хит», «−30%»)',
                'name'         => 'ps_card_badge',
                'type'         => 'text',
                'instructions' => 'Появляется поверх изображения в архиве и карточках.',
                'wrapper'      => ['width' => '60'],
            ],
            [
                'key'           => 'field_ps_card_badge_bg',
                'label'         => 'Фон бейджа карточки (HEX)',
                'name'          => 'ps_card_badge_bg',
                'type'          => 'color_picker',
                'instructions'  => 'По умолчанию #D87A4A',
                'default_value' => '',
                'wrapper'       => ['width' => '20'],
            ],
            [
                'key'           => 'field_ps_card_badge_fg',
                'label'         => 'Текст бейджа карточки (HEX)',
                'name'          => 'ps_card_badge_fg',
                'type'          => 'color_picker',
                'instructions'  => 'По умолчанию #FFFFFF',
                'default_value' => '',
                'wrapper'       => ['width' => '20'],
            ],

            /* ── Subtitle ── */
            [
                'key'   => 'field_ps_subtitle',
                'label' => 'Подзаголовок (hero)',
                'name'  => 'ps_subtitle',
                'type'  => 'text',
            ],

            /* ── Stats bar ── */
            [
                'key'          => 'field_ps_stats',
                'label'        => 'Ключевые цифры (статистика)',
                'name'         => 'ps_stats',
                'type'         => 'repeater',
                'layout'       => 'table',
                'min'          => 0,
                'max'          => 6,
                'button_label' => 'Добавить цифру',
                'sub_fields'   => [
                    [
                        'key'         => 'field_ps_stat_number',
                        'label'       => 'Значение',
                        'name'        => 'number',
                        'type'        => 'text',
                        'placeholder' => '1 000+',
                    ],
                    [
                        'key'         => 'field_ps_stat_label',
                        'label'       => 'Подпись',
                        'name'        => 'label',
                        'type'        => 'text',
                        'placeholder' => 'клиентов',
                    ],
                ],
            ],

            /* ── Benefits / Features ── */
            [
                'key'          => 'field_ps_benefits',
                'label'        => 'Преимущества / Что входит',
                'name'         => 'benefits',
                'type'         => 'repeater',
                'layout'       => 'table',
                'min'          => 0,
                'max'          => 10,
                'button_label' => 'Добавить пункт',
                'sub_fields'   => [
                    [
                        'key'         => 'field_ps_benefit_text',
                        'label'       => 'Пункт',
                        'name'        => 'text',
                        'type'        => 'text',
                        'placeholder' => 'Онлайн-встреча в удобном мессенджере',
                    ],
                ],
            ],

            /* ── Price ── */
            [
                'key'          => 'field_ps_price',
                'label'        => 'Цена',
                'name'         => 'price',
                'type'         => 'text',
                'placeholder'  => '5 900',
                'instructions' => 'Только число (или текст типа «от 5 000»). Знак ₽ добавляется автоматически.',
            ],
            [
                'key'         => 'field_ps_price_old',
                'label'       => 'Старая цена (перечёркнутая)',
                'name'        => 'ps_price_old',
                'type'        => 'text',
                'placeholder' => '9 900',
            ],

            /* ── Delivery / Format ── */
            [
                'key'          => 'field_ps_delivery_method',
                'label'        => 'Формат / способ доставки',
                'name'         => 'ps_delivery_method',
                'type'         => 'text',
                'placeholder'  => 'Zoom / Telegram / Skype',
                'instructions' => 'Отображается под кнопкой «Купить» и в архивных карточках.',
            ],

            /* ── Flexible description ── */
            [
                'key'          => 'field_ps_description',
                'label'        => 'Описание (гибкий контент)',
                'name'         => 'ps_description',
                'type'         => 'flexible_content',
                'button_label' => 'Добавить блок',
                'layouts'      => [

                    /* layout: text */
                    [
                        'key'        => 'layout_ps_desc_text',
                        'name'       => 'text',
                        'label'      => 'Текст',
                        'display'    => 'block',
                        'sub_fields' => [
                            [
                                'key'   => 'field_ps_desc_text_content',
                                'label' => 'Контент',
                                'name'  => 'content',
                                'type'  => 'wysiwyg',
                                'toolbar' => 'full',
                                'media_upload' => 0,
                            ],
                        ],
                    ],

                    /* layout: quote */
                    [
                        'key'        => 'layout_ps_desc_quote',
                        'name'       => 'quote',
                        'label'      => 'Цитата',
                        'display'    => 'block',
                        'sub_fields' => [
                            [
                                'key'   => 'field_ps_desc_quote_text',
                                'label' => 'Текст цитаты',
                                'name'  => 'text',
                                'type'  => 'textarea',
                                'rows'  => 3,
                            ],
                        ],
                    ],

                    /* layout: use_cases */
                    [
                        'key'        => 'layout_ps_desc_use_cases',
                        'name'       => 'use_cases',
                        'label'      => 'Сценарии использования',
                        'display'    => 'block',
                        'sub_fields' => [
                            [
                                'key'          => 'field_ps_desc_uc_items',
                                'label'        => 'Карточки',
                                'name'         => 'items',
                                'type'         => 'repeater',
                                'layout'       => 'table',
                                'button_label' => 'Добавить сценарий',
                                'sub_fields'   => [
                                    [
                                        'key'   => 'field_ps_desc_uc_title',
                                        'label' => 'Заголовок',
                                        'name'  => 'title',
                                        'type'  => 'text',
                                    ],
                                    [
                                        'key'   => 'field_ps_desc_uc_desc',
                                        'label' => 'Описание',
                                        'name'  => 'desc',
                                        'type'  => 'text',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            /* ── Gallery ── */
            [
                'key'           => 'field_ps_gallery',
                'label'         => 'Галерея (hero-изображения)',
                'name'          => 'ps_gallery',
                'type'          => 'gallery',
                'return_format' => 'array',
                'instructions'  => 'Первое изображение — главный hero. Остальные — превью (карусель).',
            ],

            /* ── Reviews (связь с отзывами) ── */
            [
                'key'           => 'field_ps_reviews',
                'label'         => 'Отзывы',
                'name'          => 'reviews',
                'type'          => 'relationship',
                'post_type'     => ['review'],
                'filters'       => ['search'],
                'return_format' => 'object',
                'instructions'  => 'Выберите отзывы, которые будут отображаться на странице продукта.',
            ],
        ],

        /* Location: consultation OR course */
        'location' => [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'consultation',
                ],
            ],
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'course',
                ],
            ],
        ],

        'menu_order'            => 10,
        'position'              => 'normal',
        'style'                 => 'default',
        'hide_on_screen'        => ['the_content'],
    ]);


    /* ═══════════════════════════════════════════════════════
       2.  CONSULTATION-SPECIFIC fields
    ═══════════════════════════════════════════════════════ */
    acf_add_local_field_group([
        'key'    => 'group_consultation_specific',
        'title'  => 'Консультация — специалист и тарифы',
        'fields' => [
            [
                'key'           => 'field_cns_doctor',
                'label'         => 'Специалист (врач)',
                'name'          => 'consultation_doctor',
                'type'          => 'post_object',
                'post_type'     => ['doctor'],
                'allow_null'    => 1,
                'multiple'      => 0,
                'return_format' => 'object',
                'ui'            => 1,
            ],
            [
                'key'           => 'field_cns_author_heading',
                'label'         => 'Заголовок секции «Специалист»',
                'name'          => 'consultation_author_heading',
                'type'          => 'text',
                'placeholder'   => 'Ведёт ваш <em>врач-нутрициолог</em>',
                'instructions'  => 'HTML разрешён. Можно использовать <em> для курсивного акцента.',
                'default_value' => 'Ведёт ваш <em>врач-нутрициолог</em>',
            ],

            /* ══════════════════════════════════════════
               PRICING TIERS — 3 опции консультации
            ══════════════════════════════════════════ */
            [
                'key'          => 'field_cns_tiers',
                'label'        => 'Тарифы консультации',
                'name'         => 'ps_pricing_tiers',
                'type'         => 'repeater',
                'layout'       => 'block',
                'min'          => 0,
                'max'          => 3,
                'button_label' => 'Добавить тариф',
                'instructions' => 'Рекомендуется 3 тарифа. Средний автоматически выделяется как «Популярный».',
                'sub_fields'   => [
                    [
                        'key'         => 'field_cns_tier_name',
                        'label'       => 'Название тарифа',
                        'name'        => 'tier_name',
                        'type'        => 'text',
                        'placeholder' => 'Базовая',
                        'wrapper'     => ['width' => '50'],
                    ],
                    [
                        'key'         => 'field_cns_tier_subtitle',
                        'label'       => 'Подзаголовок',
                        'name'        => 'tier_subtitle',
                        'type'        => 'text',
                        'placeholder' => 'Разовая консультация 60 минут',
                        'wrapper'     => ['width' => '50'],
                    ],
                    [
                        'key'          => 'field_cns_tier_badge',
                        'label'        => 'Бейдж тарифа (например, «Хит», «Выбор врача»)',
                        'name'         => 'tier_badge',
                        'type'         => 'text',
                        'placeholder'  => 'Оставьте пустым, чтобы скрыть',
                        'instructions' => 'Появляется над тарифом. Если задан — тариф выделяется как акцентный.',
                        'wrapper'      => ['width' => '40'],
                    ],
                    [
                        'key'           => 'field_cns_tier_badge_bg',
                        'label'         => 'Фон бейджа (HEX)',
                        'name'          => 'tier_badge_bg',
                        'type'          => 'color_picker',
                        'default_value' => '',
                        'wrapper'       => ['width' => '20'],
                    ],
                    [
                        'key'           => 'field_cns_tier_badge_fg',
                        'label'         => 'Текст бейджа (HEX)',
                        'name'          => 'tier_badge_fg',
                        'type'          => 'color_picker',
                        'default_value' => '',
                        'wrapper'       => ['width' => '20'],
                    ],
                    [
                        'key'           => 'field_cns_tier_cta',
                        'label'         => 'Текст кнопки',
                        'name'          => 'tier_cta_text',
                        'type'          => 'text',
                        'placeholder'   => 'Записаться',
                        'default_value' => 'Записаться',
                        'wrapper'       => ['width' => '20'],
                    ],
                    [
                        'key'         => 'field_cns_tier_price',
                        'label'       => 'Цена',
                        'name'        => 'tier_price',
                        'type'        => 'text',
                        'placeholder' => '5 900',
                        'wrapper'     => ['width' => '50'],
                    ],
                    [
                        'key'         => 'field_cns_tier_price_old',
                        'label'       => 'Старая цена',
                        'name'        => 'tier_price_old',
                        'type'        => 'text',
                        'placeholder' => '8 900',
                        'wrapper'     => ['width' => '50'],
                    ],
                    [
                        'key'          => 'field_cns_tier_features',
                        'label'        => 'Что входит в тариф',
                        'name'         => 'tier_features',
                        'type'         => 'repeater',
                        'layout'       => 'table',
                        'button_label' => 'Добавить пункт',
                        'sub_fields'   => [
                            [
                                'key'         => 'field_cns_tier_feature_text',
                                'label'       => 'Пункт',
                                'name'        => 'text',
                                'type'        => 'text',
                                'placeholder' => 'Видеовстреча 60 минут',
                            ],
                            [
                                'key'           => 'field_cns_tier_feature_included',
                                'label'         => 'Включён',
                                'name'          => 'included',
                                'type'          => 'true_false',
                                'default_value' => 1,
                                'ui'            => 1,
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'consultation',
                ],
            ],
        ],
        'menu_order' => 20,
        'position'   => 'normal',
    ]);


    /* ═══════════════════════════════════════════════════════
       3.  COURSE-SPECIFIC fields
    ═══════════════════════════════════════════════════════ */
    acf_add_local_field_group([
        'key'    => 'group_course_specific',
        'title'  => 'Курс — автор, программа, тарифы и места',
        'fields' => [

            /* ── Author ── */
            [
                'key'           => 'field_crs_author',
                'label'         => 'Автор курса',
                'name'          => 'course_author',
                'type'          => 'post_object',
                'post_type'     => ['doctor'],
                'allow_null'    => 1,
                'multiple'      => 0,
                'return_format' => 'object',
                'ui'            => 1,
            ],

            /* ── Program / TOC ── */
            [
                'key'          => 'field_crs_contents',
                'label'        => 'Программа курса (оглавление)',
                'name'         => 'ps_contents',
                'type'         => 'repeater',
                'layout'       => 'block',
                'button_label' => 'Добавить модуль',
                'sub_fields'   => [
                    [
                        'key'   => 'field_crs_module_title',
                        'label' => 'Название модуля',
                        'name'  => 'title',
                        'type'  => 'text',
                    ],
                    [
                        'key'   => 'field_crs_module_desc',
                        'label' => 'Краткое описание',
                        'name'  => 'desc',
                        'type'  => 'textarea',
                        'rows'  => 2,
                    ],
                ],
            ],

            /* ══════════════════════════════════════════
               PRICING TIERS
            ══════════════════════════════════════════ */
            [
                'key'          => 'field_crs_tiers',
                'label'        => 'Тарифные планы',
                'name'         => 'ps_pricing_tiers',
                'type'         => 'repeater',
                'layout'       => 'block',
                'min'          => 0,
                'max'          => 3,
                'button_label' => 'Добавить тариф',
                'instructions' => 'Рекомендуется 3 тарифа. Средний автоматически выделяется как «Популярный».',
                'sub_fields'   => [

                    [
                        'key'         => 'field_crs_tier_name',
                        'label'       => 'Название тарифа',
                        'name'        => 'tier_name',
                        'type'        => 'text',
                        'placeholder' => 'Самостоятельно',
                        'wrapper'     => ['width' => '50'],
                    ],
                    [
                        'key'         => 'field_crs_tier_subtitle',
                        'label'       => 'Подзаголовок',
                        'name'        => 'tier_subtitle',
                        'type'        => 'text',
                        'placeholder' => 'Учитесь в своём темпе',
                        'wrapper'     => ['width' => '50'],
                    ],

                    [
                        'key'           => 'field_crs_tier_popular',
                        'label'         => 'Отметить как «Популярный»',
                        'name'          => 'tier_is_popular',
                        'type'          => 'true_false',
                        'default_value' => 0,
                        'ui'            => 1,
                        'wrapper'       => ['width' => '25'],
                    ],
                    [
                        'key'         => 'field_crs_tier_price',
                        'label'       => 'Цена',
                        'name'        => 'tier_price',
                        'type'        => 'text',
                        'placeholder' => '9 900',
                        'wrapper'     => ['width' => '25'],
                    ],
                    [
                        'key'         => 'field_crs_tier_price_old',
                        'label'       => 'Старая цена',
                        'name'        => 'tier_price_old',
                        'type'        => 'text',
                        'placeholder' => '14 900',
                        'wrapper'     => ['width' => '25'],
                    ],
                    [
                        'key'         => 'field_crs_tier_cta',
                        'label'       => 'Текст кнопки',
                        'name'        => 'tier_cta_text',
                        'type'        => 'text',
                        'placeholder' => 'Купить',
                        'default_value' => 'Купить',
                        'wrapper'     => ['width' => '25'],
                    ],

                    [
                        'key'          => 'field_crs_tier_features',
                        'label'        => 'Что входит в тариф',
                        'name'         => 'tier_features',
                        'type'         => 'repeater',
                        'layout'       => 'table',
                        'button_label' => 'Добавить пункт',
                        'sub_fields'   => [
                            [
                                'key'         => 'field_crs_tier_feature_text',
                                'label'       => 'Пункт',
                                'name'        => 'text',
                                'type'        => 'text',
                                'placeholder' => 'Доступ к видеоурокам навсегда',
                            ],
                            [
                                'key'           => 'field_crs_tier_feature_included',
                                'label'         => 'Включён',
                                'name'          => 'included',
                                'type'          => 'true_false',
                                'default_value' => 1,
                                'ui'            => 1,
                            ],
                        ],
                    ],
                ],
            ],

            /* ══════════════════════════════════════════
               SEATS / МЕСТА
            ══════════════════════════════════════════ */
            [
                'key'           => 'field_crs_seats_enabled',
                'label'         => 'Включить счётчик мест',
                'name'          => 'ps_seats_enabled',
                'type'          => 'true_false',
                'default_value' => 0,
                'ui'            => 1,
                'ui_on_text'    => 'Вкл',
                'ui_off_text'   => 'Выкл',
            ],
            [
                'key'               => 'field_crs_seats_base',
                'label'             => 'Базовое кол-во мест (максимум)',
                'name'              => 'ps_seats_base',
                'type'              => 'number',
                'min'               => 1,
                'default_value'     => 10,
                'instructions'      => 'До этого значения сбрасывается счётчик мест после каждого периода.',
                'conditional_logic' => [
                    [['field' => 'field_crs_seats_enabled', 'operator' => '==', 'value' => 1]],
                ],
                'wrapper'           => ['width' => '50'],
            ],
            [
                'key'               => 'field_crs_seats_period',
                'label'             => 'Период сброса',
                'name'              => 'ps_seats_reset_period',
                'type'              => 'select',
                'choices'           => [
                    'never'    => 'Не сбрасывать',
                    'daily'    => 'Ежедневно',
                    'weekly'   => 'Еженедельно',
                    'biweekly' => 'Раз в 2 недели',
                    'monthly'  => 'Ежемесячно',
                ],
                'default_value'     => 'monthly',
                'conditional_logic' => [
                    [['field' => 'field_crs_seats_enabled', 'operator' => '==', 'value' => 1]],
                ],
                'wrapper'           => ['width' => '50'],
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'course',
                ],
            ],
        ],
        'menu_order' => 20,
        'position'   => 'normal',
    ]);


    /* ═══════════════════════════════════════════════════════
       4.  DOCTOR / SPECIALIST fields
    ═══════════════════════════════════════════════════════ */
    acf_add_local_field_group([
        'key'    => 'group_doctor',
        'title'  => 'Специалист — профиль',
        'fields' => [

            [
                'key'   => 'field_doc_specialty',
                'label' => 'Специализация',
                'name'  => 'doc_specialty',
                'type'  => 'text',
                'placeholder' => 'Диетолог-нутрициолог',
            ],

            [
                'key'           => 'field_doc_verified',
                'label'         => 'Верифицирован',
                'name'          => 'doc_verified',
                'type'          => 'true_false',
                'default_value' => 1,
                'ui'            => 1,
                'ui_on_text'    => 'Да',
                'ui_off_text'   => 'Нет',
            ],

            [
                'key'          => 'field_doc_tags',
                'label'        => 'Теги / регалии специалиста',
                'name'         => 'doc_tags',
                'type'         => 'repeater',
                'layout'       => 'table',
                'button_label' => 'Добавить тег',
                'sub_fields'   => [
                    [
                        'key'         => 'field_doc_tag_text',
                        'label'       => 'Тег',
                        'name'        => 'text',
                        'type'        => 'text',
                        'placeholder' => 'Кандидат медицинских наук',
                    ],
                ],
            ],

            [
                'key'          => 'field_doc_stats',
                'label'        => 'Статистика специалиста',
                'name'         => 'doc_stats',
                'type'         => 'repeater',
                'layout'       => 'table',
                'max'          => 4,
                'button_label' => 'Добавить показатель',
                'sub_fields'   => [
                    [
                        'key'         => 'field_doc_stat_value',
                        'label'       => 'Значение',
                        'name'        => 'value',
                        'type'        => 'text',
                        'placeholder' => '10+',
                    ],
                    [
                        'key'         => 'field_doc_stat_label',
                        'label'       => 'Подпись',
                        'name'        => 'label',
                        'type'        => 'text',
                        'placeholder' => 'лет практики',
                    ],
                ],
            ],

            [
                'key'          => 'field_doc_desc',
                'label'        => 'Биография / описание',
                'name'         => 'doctor_desc',
                'type'         => 'wysiwyg',
                'toolbar'      => 'basic',
                'media_upload' => 0,
            ],

            [
                'key'   => 'field_doc_quote',
                'label' => 'Цитата специалиста',
                'name'  => 'doc_quote',
                'type'  => 'textarea',
                'rows'  => 2,
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'doctor',
                ],
            ],
        ],
        'menu_order'     => 10,
        'position'       => 'normal',
        'hide_on_screen' => ['the_content'],
    ]);


    /* ═══════════════════════════════════════════════════════
       5.  REVIEW fields
          (The CPT exists; these define its ACF structure)
    ═══════════════════════════════════════════════════════ */
    acf_add_local_field_group([
        'key'    => 'group_review',
        'title'  => 'Отзыв',
        'fields' => [

            [
                'key'     => 'field_rv_type',
                'label'   => 'Тип отзыва',
                'name'    => 'type',
                'type'    => 'select',
                'choices' => [
                    'text'         => 'Текст',
                    'image'        => 'Изображение (скриншот)',
                    'video'        => 'Видео',
                    'before_after' => 'До / После',
                ],
                'default_value' => 'text',
                'ui'            => 1,
            ],

            [
                'key'          => 'field_rv_before_text',
                'label'        => 'Текст отзыва / «Было»',
                'name'         => 'before_text',
                'type'         => 'wysiwyg',
                'toolbar'      => 'basic',
                'media_upload' => 0,
            ],

            [
                'key'               => 'field_rv_after_text',
                'label'             => '«Стало» (только для типа До/После)',
                'name'              => 'after_text',
                'type'              => 'wysiwyg',
                'toolbar'           => 'basic',
                'media_upload'      => 0,
                'conditional_logic' => [
                    [
                        [
                            'field'    => 'field_rv_type',
                            'operator' => '==',
                            'value'    => 'before_after',
                        ],
                    ],
                ],
            ],

            [
                'key'           => 'field_rv_media',
                'label'         => 'Фото / скриншот',
                'name'          => 'media',
                'type'          => 'image',
                'return_format' => 'url',
                'preview_size'  => 'medium',
            ],

            [
                'key'               => 'field_rv_video_url',
                'label'             => 'Ссылка на видео (YouTube или .mp4)',
                'name'              => 'video_url',
                'type'              => 'url',
                'conditional_logic' => [
                    [
                        [
                            'field'    => 'field_rv_type',
                            'operator' => '==',
                            'value'    => 'video',
                        ],
                    ],
                ],
            ],

            [
                'key'       => 'field_rv_related_diseases',
                'label'     => 'Связанные диагнозы',
                'name'      => 'related_diseases',
                'type'      => 'relationship',
                'post_type' => ['disease'],
                'filters'   => ['search'],
                'return_format' => 'object',
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'review',
                ],
            ],
        ],
        'menu_order'     => 10,
        'position'       => 'normal',
        'hide_on_screen' => ['the_content'],
    ]);
});
