<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use WP_Query;

class Products extends Composer
{
    protected static $views = [
        'template-products',
    ];

    const PER_PAGE = 20;

    public function with()
    {
        $data = $this->getProducts();

        return [
            'products'      => $data['items'],
            'totalProducts' => $data['total'],
            'currentPage'   => $data['paged'],
            'totalPages'    => $data['total_pages'],
            'perPage'       => self::PER_PAGE,
            'categories'    => $this->getCategories(),
        ];
    }

    protected function getCategories()
    {
        return get_terms([
            'taxonomy'   => 'disease_category',
            'hide_empty' => false,
        ]);
    }

    protected function getProducts()
    {
        $allowedTypes = ['consultation', 'course', 'book'];

        $postTypes = $allowedTypes;
        if (!empty($_GET['type'])) {
            $requested = array_intersect(
                explode(',', sanitize_text_field($_GET['type'])),
                $allowedTypes
            );
            if (!empty($requested)) {
                $postTypes = array_values($requested);
            }
        }

        $args = [
            'post_type'      => $postTypes,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        ];

        // Resolve category filter.
        // Products link to categories in two ways:
        //   - book/course: via ACF 'related_diseases' field → disease → disease_category
        //   - consultation: via direct disease_category taxonomy term assignment
        $filterCatSlugs  = [];
        $filterDiseaseIds = null;

        if (!empty($_GET['category'])) {
            $filterCatSlugs = explode(',', sanitize_text_field($_GET['category']));

            // Get IDs of diseases that belong to the selected categories
            $diseaseIds = get_posts([
                'post_type'      => 'disease',
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'tax_query'      => [[
                    'taxonomy' => 'disease_category',
                    'field'    => 'slug',
                    'terms'    => $filterCatSlugs,
                    'operator' => 'IN',
                ]],
            ]);

            $filterDiseaseIds = array_map('intval', (array) $diseaseIds);
        }

        $query     = new WP_Query($args);
        $raw_posts = $query->posts;
        $results   = [];

        foreach ($raw_posts as $post) {

            // ── Category filter ───────────────────────────────────────────
            if (!empty($filterCatSlugs)) {
                $passes = false;

                // 1. Direct taxonomy (works for consultation after tax registration fix)
                $directTerms = wp_get_post_terms($post->ID, 'disease_category', ['fields' => 'slugs']);
                if (! is_wp_error($directTerms) && ! empty(array_intersect($directTerms, $filterCatSlugs))) {
                    $passes = true;
                }

                // 2. Via related_diseases → disease → category (works for book/course)
                if (! $passes && $filterDiseaseIds !== null) {
                    $related = function_exists('get_field') ? (get_field('related_diseases', $post->ID) ?: []) : [];
                    $relatedIds = array_map(
                        fn($d) => is_object($d) ? (int) $d->ID : (int) $d,
                        (array) $related
                    );
                    if (! empty(array_intersect($relatedIds, $filterDiseaseIds))) {
                        $passes = true;
                    }
                }

                if (! $passes) {
                    continue;
                }
            }

            // ── Price filter ──────────────────────────────────────────────
            $price = function_exists('get_field') ? get_field('price', $post->ID) : 0;
            $price = (float) str_replace([' ', ','], ['', '.'], $price);

            if (!empty($_GET['price'])) {
                $pModes   = explode(',', sanitize_text_field($_GET['price']));
                $passPrice = false;
                if (in_array('up_to_2000',   $pModes) && $price <= 2000)                   $passPrice = true;
                if (in_array('2000_to_5000', $pModes) && $price >= 2000 && $price <= 5000) $passPrice = true;
                if (in_array('over_5000',    $pModes) && $price > 5000)                    $passPrice = true;
                if (! $passPrice) continue;
            }

            // ── Features ──────────────────────────────────────────────────
            $features = [];
            if (function_exists('get_field')) {
                $benefits = get_field('benefits', $post->ID);
                if (! empty($benefits) && is_array($benefits)) {
                    $features = array_slice($benefits, 0, 3);
                } else {
                    $features = match ($post->post_type) {
                        'consultation' => [['text' => 'Онлайн-встреча'], ['text' => 'Анализ симптомов'], ['text' => 'Персональный план']],
                        'course'       => [['text' => 'Видеоуроки'], ['text' => 'Домашние задания'], ['text' => 'Чат поддержки']],
                        default        => [['text' => 'Схема нутрицевтиков'], ['text' => 'Протокол питания'], ['text' => 'Доступ навсегда']],
                    };
                }
            }

            // ── Doctor data ───────────────────────────────────────────────
            $authorData = [];
            if ($post->post_type === 'consultation' && function_exists('get_field')) {
                $doc = get_field('consultation_doctor', $post->ID) ?: get_field('service_doctor', $post->ID);
                if (! empty($doc) && is_array($doc)) {
                    $doc_id     = $doc[0]->ID;
                    $authorData = [
                        'name'       => get_the_title($doc_id),
                        'specialty'  => get_field('doc_specialty', $doc_id) ?: 'Специалист',
                        'experience' => get_field('doc_experience', $doc_id) ?: '',
                        'avatar'     => get_the_post_thumbnail_url($doc_id, 'thumbnail') ?: '',
                    ];
                }
            }

            $typeLabel = match ($post->post_type) {
                'consultation' => 'Консультация',
                'course'       => 'Курс',
                default        => 'МПО',
            };

            $results[] = [
                'id'         => $post->ID,
                'post_type'  => $post->post_type,
                'title'      => get_the_title($post->ID),
                'url'        => get_permalink($post->ID),
                'image'      => get_the_post_thumbnail_url($post->ID, 'large') ?: '',
                'price'      => $price,
                'price_old'  => function_exists('get_field') ? (get_field('book_price_old', $post->ID) ?: '') : '',
                'badge'      => function_exists('get_field') ? (get_field('ps_card_badge', $post->ID) ?: '') : '',
                'delivery'   => function_exists('get_field') ? (get_field('ps_delivery_method', $post->ID) ?: '') : '',
                'features'   => $features,
                'author'     => $authorData,
                'excerpt'    => get_the_excerpt($post->ID) ?: wp_trim_words($post->post_content, 15),
                'type_label' => $typeLabel,
            ];
        }

        // ── Sorting ───────────────────────────────────────────────────────
        $sort = sanitize_text_field($_GET['sort'] ?? 'hierarchy');

        if ($sort === 'price_asc') {
            usort($results, fn($a, $b) => $a['price'] <=> $b['price']);
        } elseif ($sort === 'price_desc') {
            usort($results, fn($a, $b) => $b['price'] <=> $a['price']);
        } else {
            $weights = ['consultation' => 0, 'course' => 1, 'book' => 2];
            usort($results, fn($a, $b) => $weights[$a['post_type']] <=> $weights[$b['post_type']]);
        }

        // ── Pagination ────────────────────────────────────────────────────
        $total      = count($results);
        $perPage    = self::PER_PAGE;
        $totalPages = max(1, (int) ceil($total / $perPage));
        $paged      = max(1, min((int) ($_GET['paged'] ?? 1), $totalPages));
        $items      = array_slice($results, ($paged - 1) * $perPage, $perPage);

        return [
            'items'       => $items,
            'total'       => $total,
            'paged'       => $paged,
            'total_pages' => $totalPages,
        ];
    }
}
