<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use WP_Query;

class Search extends Composer
{
    protected static $views = ['search'];

    public function with()
    {
        $query   = get_search_query();
        $type    = sanitize_text_field($_GET['type'] ?? '');
        $results = $this->getResults($query, $type);

        return [
            'searchQuery'  => $query,
            'activeType'   => $type,
            'results'      => $results,
            'totalResults' => count($results),
        ];
    }

    protected function getResults(string $query, string $type): array
    {
        if (!$query) {
            return [];
        }

        $allowed   = ['book', 'course', 'consultation'];
        $postTypes = ($type && in_array($type, $allowed)) ? [$type] : $allowed;

        $wpQuery = new WP_Query([
            'post_type'      => $postTypes,
            'post_status'    => 'publish',
            's'              => $query,
            'posts_per_page' => 60,
            'orderby'        => 'relevance',
        ]);

        $results = [];

        foreach ($wpQuery->posts as $post) {
            $price = function_exists('get_field') ? get_field('price', $post->ID) : 0;
            $price = (float) str_replace([' ', ','], ['', '.'], $price);

            $features = [];
            if (function_exists('get_field')) {
                $benefits = get_field('benefits', $post->ID);
                if (!empty($benefits) && is_array($benefits)) {
                    $features = array_slice($benefits, 0, 3);
                } else {
                    $features = match ($post->post_type) {
                        'consultation' => [['text' => 'Онлайн-встреча'], ['text' => 'Анализ симптомов'], ['text' => 'Персональный план']],
                        'course'       => [['text' => 'Видеоуроки'], ['text' => 'Домашние задания'], ['text' => 'Чат поддержки']],
                        default        => [['text' => 'Схема нутрицевтиков'], ['text' => 'Протокол питания'], ['text' => 'Доступ навсегда']],
                    };
                }
            }

            $authorData = [];
            if ($post->post_type === 'consultation' && function_exists('get_field')) {
                $doc = get_field('consultation_doctor', $post->ID) ?: get_field('service_doctor', $post->ID);
                if (!empty($doc) && is_array($doc)) {
                    $doc_id     = $doc[0]->ID;
                    $authorData = [
                        'name'       => get_the_title($doc_id),
                        'specialty'  => get_field('doc_specialty', $doc_id) ?: 'Специалист',
                        'experience' => get_field('doc_experience', $doc_id) ?: '',
                        'avatar'     => get_the_post_thumbnail_url($doc_id, 'thumbnail') ?: '',
                    ];
                }
            }

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
                'excerpt'    => get_the_excerpt($post->ID) ?: wp_trim_words($post->post_content, 20),
                'type_label' => match ($post->post_type) {
                    'consultation' => 'Консультация',
                    'course'       => 'Курс',
                    default        => 'МПО',
                },
            ];
        }

        return $results;
    }
}
