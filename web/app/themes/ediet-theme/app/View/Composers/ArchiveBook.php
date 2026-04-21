<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class ArchiveBook extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'archive-book',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'books' => $this->getBooks(),
        ];
    }

    /**
     * Get the books for the archive grid
     *
     * @return array
     */
    protected function getBooks()
    {
        global $wp_query;
        $books = [];

        if (empty($wp_query->posts)) {
            return [];
        }

        foreach ($wp_query->posts as $post) {
            $features = get_field('book_features', $post->ID) ?: [];
            
            $books[] = [
                'id'         => $post->ID,
                'title'      => get_the_title($post->ID),
                'url'        => get_permalink($post->ID),
                'type_label' => 'Книга',
                'features'   => array_slice($features, 0, 4),
                'price'      => get_field('price', $post->ID) ?: '',
                'price_old'  => get_field('book_price_old', $post->ID) ?: '',
                'delivery'   => get_field('book_delivery_note', $post->ID) ?: '',
                'image'      => get_the_post_thumbnail_url($post->ID, 'medium') ?: '',
                'badge'      => get_field('ps_card_badge', $post->ID) ?: '',
            ];
        }

        return $books;
    }
}
