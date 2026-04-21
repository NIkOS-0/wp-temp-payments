<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class SingleCourse extends Composer
{
    protected static $views = [
        'single-course',
    ];

    public function with()
    {
        $id = get_the_ID();

        return [
            'course'      => $this->getCourseData($id),
            'author'      => $this->getAuthorData($id),
            'reviews'     => $this->getReviewsData($id),
            'cross_sells' => $this->getRecommendations($id),
        ];
    }

    protected function getCourseData($id)
    {
        $features = [];
        $benefits = get_field('benefits', $id);
        if (!empty($benefits) && is_array($benefits)) {
            $features = array_slice($benefits, 0, 4);
        } else {
            $features = [['text' => 'Доступ к видеоурокам навсегда'], ['text' => 'Практические домашние задания'], ['text' => 'Полное сопровождение куратора']];
        }

        return [
            'title'          => get_the_title($id),
            'top_labels'     => get_field('ps_top_labels', $id) ?: [['text' => 'Курс', 'style' => 'pdf']],
            'image_badge'    => get_field('ps_card_badge', $id) ?: '',
            'subtitle'       => get_field('ps_subtitle', $id) ?: 'Обучающий курс',
            'stats'          => get_field('ps_stats', $id) ?: [],
            'features'       => $features,
            'price'          => get_field('price', $id) ?: '',
            'price_old'      => get_field('ps_price_old', $id) ?: '',
            'delivery_note'  => get_field('ps_delivery_method', $id) ?: 'Сразу после оплаты',
            'description'    => get_field('ps_description', $id) ?: [],
            'contents'       => get_field('ps_contents', $id) ?: [],
            'gallery'        => get_field('ps_gallery', $id) ?: [],
        ];
    }

    protected function getAuthorData($id)
    {
        $doc = get_field('course_author', $id) ?: get_field('service_doctor', $id);
        if (!$doc || empty($doc)) {
            return null;
        }

        $author = is_array($doc) ? $doc[0] : $doc;
        $author_id = $author->ID;

        return [
            'name'       => get_the_title($author_id),
            'avatar_url' => get_the_post_thumbnail_url($author_id, 'medium'),
            'spec'       => get_field('doc_specialty', $author_id) ?: 'Специалист',
            'verified'   => get_field('doc_verified', $author_id) ?: true,
            'tags'       => get_field('doc_tags', $author_id) ?: [],
            'stats'      => get_field('doc_stats', $author_id) ?: [],
            'bio'        => get_field('doctor_desc', $author_id) ?: get_post_field('post_content', $author_id),
            'quote'      => get_field('doc_quote', $author_id) ?: '',
        ];
    }

    protected function getReviewsData($id)
    {
        $reviews_field = get_field('reviews', $id);
        if (!$reviews_field || empty($reviews_field)) return [];
        return is_array($reviews_field) ? $reviews_field : [$reviews_field];
    }

    protected function getRecommendations($post_id)
    {
        $query_args = [
            'post_type' => ['book', 'service'],
            'posts_per_page' => 4,
            'orderby' => 'rand'
        ];

        $recs = get_posts($query_args);
        $rec_data = [];
        foreach ($recs as $rec) {
            $type_label = $rec->post_type === 'service' ? 'Консультация' : 'Книга';
            $features = get_field('benefits', $rec->ID) ?: [];
            
            $rec_data[] = [
                'id' => $rec->ID,
                'title' => get_the_title($rec->ID),
                'url' => get_permalink($rec->ID),
                'type_label' => $type_label,
                'features' => array_slice($features, 0, 3), // max 3 features
                'price' => get_field('price', $rec->ID) ?: '',
                'price_old' => get_field('book_price_old', $rec->ID) ?: get_field('ps_price_old', $rec->ID),
                'delivery' => get_field('ps_delivery_method', $rec->ID) ?: get_field('book_delivery_note', $rec->ID),
                'image' => get_the_post_thumbnail_url($rec->ID, 'medium') ?: '',
                'badge' => get_field('ps_card_badge', $rec->ID) ?: '',
            ];
        }
        return $rec_data;
    }
}
