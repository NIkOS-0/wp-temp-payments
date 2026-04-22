<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class ArchiveConsultation extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'archive-consultation',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'consultations' => $this->getConsultations(),
        ];
    }

    /**
     * Get the consultations for the archive grid
     *
     * @return array
     */
    protected function getConsultations()
    {
        global $wp_query;
        $consultations = [];

        if (empty($wp_query->posts)) {
            return [];
        }

        foreach ($wp_query->posts as $post) {
            $features = get_field('benefits', $post->ID) ?: [];
            
            $consultations[] = [
                'id'         => $post->ID,
                'title'      => get_the_title($post->ID),
                'url'        => get_permalink($post->ID),
                'type_label' => 'Консультация',
                'features'   => array_slice($features, 0, 4),
                'price'      => get_field('price', $post->ID) ?: '',
                'price_old'  => get_field('price_old', $post->ID) ?: '',
                'delivery'   => get_field('delivery_note', $post->ID) ?: '',
                'image'      => get_the_post_thumbnail_url($post->ID, 'medium') ?: '',
                'badge'      => get_field('ps_card_badge', $post->ID) ?: '',
            ];
        }

        return $consultations;
    }
}
