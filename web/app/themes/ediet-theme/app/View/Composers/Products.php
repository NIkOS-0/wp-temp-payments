<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use WP_Query;

class Products extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'template-products',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'products' => $this->getProducts(),
            'categories' => $this->getCategories(),
        ];
    }

    /**
     * Retrieves all categories (disease_category)
     */
    protected function getCategories()
    {
        return get_terms([
            'taxonomy' => 'disease_category',
            'hide_empty' => false,
        ]);
    }

    /**
     * Retrieves products based on filters.
     */
    protected function getProducts()
    {
        // Setup initial args
        $args = [
            'post_type'      => ['consultation', 'course', 'book'],
            'post_status'    => 'publish',
            'posts_per_page' => -1, // get all to sort effectively
        ];

        // 1. Filter by category
        if (!empty($_GET['category'])) {
            $cat_input = sanitize_text_field($_GET['category']);
            $args['tax_query'] = [
                [
                    'taxonomy' => 'disease_category',
                    'field'    => 'slug',
                    'terms'    => explode(',', $cat_input),
                ]
            ];
        }

        $query = new WP_Query($args);
        $raw_posts = $query->posts;

        $results = [];
        foreach ($raw_posts as $post) {
            $price = function_exists('get_field') ? get_field('price', $post->ID) : 0;
            $price = (float)str_replace(' ', '', $price);

            // Filter by price if needed
            if (!empty($_GET['price'])) {
                $pModes = explode(',', sanitize_text_field($_GET['price']));
                $passPrice = false;
                if (in_array('up_to_2000', $pModes) && $price <= 2000) $passPrice = true;
                if (in_array('2000_to_5000', $pModes) && $price >= 2000 && $price <= 5000) $passPrice = true;
                if (in_array('over_5000', $pModes) && $price > 5000) $passPrice = true;
                if (!$passPrice) continue;
            }

            // Extract features
            $features = [];
            if (function_exists('get_field')) {
                // Determine group_product_service.json field "benefits" vs "books_benefits"
                $benefits = get_field('benefits', $post->ID);
                if (!empty($benefits) && is_array($benefits)) {
                    $features = array_slice($benefits, 0, 3);
                } else {
                     // fallback
                     if ($post->post_type === 'consultation') {
                         $features = [['text' => 'Онлайн-встреча'], ['text' => 'Анализ симптомов'], ['text' => 'План лечения']];
                     } else if ($post->post_type === 'course') {
                         $features = [['text' => 'Видеоуроки'], ['text' => 'Домашние задания'], ['text' => 'Чат поддержки']];
                     } else {
                         $features = [['text' => 'Схема нутрицевтиков'], ['text' => 'Протокол питания'], ['text' => 'Доступ навсегда']];
                     }
                }
            }

            // Author for consultation
            $authorData = [];
            if ($post->post_type === 'consultation' && function_exists('get_field')) {
                $doc = get_field('consultation_doctor', $post->ID) ?: get_field('service_doctor', $post->ID);
                if (!empty($doc) && is_array($doc)) {
                    $doc_id = $doc[0]->ID;
                    $authorData = [
                        'name' => get_the_title($doc_id),
                        'specialty' => get_field('doc_specialty', $doc_id) ?: 'Специалист',
                        'experience' => get_field('doc_experience', $doc_id) ?: 'Врач высшей категории',
                        'avatar' => get_the_post_thumbnail_url($doc_id, 'thumbnail'),
                    ];
                }
            }

            $typeLabel = '';
            if ($post->post_type === 'consultation') $typeLabel = 'Консультация';
            if ($post->post_type === 'course') $typeLabel = 'Курс';
            if ($post->post_type === 'book') $typeLabel = 'МПО';

            $results[] = [
                'id'         => $post->ID,
                'post_type'  => $post->post_type,
                'title'      => get_the_title($post->ID),
                'url'        => get_permalink($post->ID),
                'image'      => get_the_post_thumbnail_url($post->ID, 'large'),
                'price'      => $price,
                'price_old'  => function_exists('get_field') ? get_field('book_price_old', $post->ID) : '',
                'badge'      => function_exists('get_field') ? get_field('ps_card_badge', $post->ID) : '',
                'delivery'   => function_exists('get_field') ? get_field('ps_delivery_method', $post->ID) : '',
                'features'   => $features,
                'author'     => $authorData,
                'excerpt'    => get_the_excerpt($post->ID) ?: wp_trim_words($post->post_content, 15),
                'type_label' => $typeLabel
            ];
        }

        // Apply Sorting
        $sort = $_GET['sort'] ?? 'hierarchy';

        if ($sort === 'price_asc') {
            usort($results, function ($a, $b) {
                return $a['price'] <=> $b['price'];
            });
        } elseif ($sort === 'price_desc') {
            usort($results, function ($a, $b) {
                return $b['price'] <=> $a['price'];
            });
        } else {
            // Hierarchy: consultation (0) -> course (1) -> book (2)
            $weights = [
                'consultation' => 0,
                'course'  => 1,
                'book'    => 2
            ];

            usort($results, function ($a, $b) use ($weights) {
                return $weights[$a['post_type']] <=> $weights[$b['post_type']];
            });
        }

        return $results;
    }
}
