<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class ArchiveCourse extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'archive-course',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'courses' => $this->getCourses(),
        ];
    }

    /**
     * Get the courses for the archive grid
     *
     * @return array
     */
    protected function getCourses()
    {
        global $wp_query;
        $courses = [];

        if (empty($wp_query->posts)) {
            return [];
        }

        foreach ($wp_query->posts as $post) {
            $features = get_field('benefits', $post->ID) ?: [];
            
            $courses[] = [
                'id'         => $post->ID,
                'title'      => get_the_title($post->ID),
                'url'        => get_permalink($post->ID),
                'type_label' => 'Курс',
                'features'   => array_slice($features, 0, 4),
                'price'      => get_field('price', $post->ID) ?: '',
                'price_old'  => get_field('ps_price_old', $post->ID) ?: '',
                'delivery'   => get_field('ps_delivery_method', $post->ID) ?: '',
                'image'      => get_the_post_thumbnail_url($post->ID, 'medium') ?: '',
                'badge'      => get_field('ps_card_badge', $post->ID) ?: '',
            ];
        }

        return $courses;
    }
}
