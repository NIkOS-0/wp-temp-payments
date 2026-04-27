<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class SingleBook extends Composer
{
    protected static $views = [
        'single-book',
    ];

    public function with()
    {
        $id = get_the_ID();

        // Cross-sells (Recommendations)
        $cross_sells = $this->getRecommendations($id);

        return [
            'book'        => $this->getBookData($id),
            'author'      => $this->getAuthorData($id),
            'reviews'     => $this->getReviewsData($id),
            'cross_sells' => $cross_sells,
        ];
    }

    protected function getBookData($id)
    {
        return [
            'title'          => get_the_title($id),
            'top_labels'     => get_field('book_top_labels', $id) ?: [],
            'image_badge'    => get_field('book_image_badge', $id) ?: '',
            'image_badge_bg' => get_field('book_image_badge_bg', $id) ?: '#B8B8B8',
            'subtitle'       => get_field('book_subtitle', $id) ?: '',
            'stats'          => get_field('book_stats', $id) ?: [],
            'features'       => get_field('book_features', $id) ?: [],
            'price'          => get_field('price', $id) ?: '',
            'price_old'      => get_field('book_price_old', $id) ?: '',
            'delivery_note'  => get_field('book_delivery_note', $id) ?: '',
            'description'    => get_field('book_description', $id) ?: [],
            'contents'       => get_field('book_contents', $id) ?: [],
            'gallery'        => get_field('book_gallery', $id) ?: [],
        ];
    }

    protected function getAuthorData($id)
    {
        $author_field = get_field('book_author', $id);
        if (!$author_field || empty($author_field)) {
            return null;
        }

        $author = is_array($author_field) ? $author_field[0] : $author_field;
        $author_id = $author->ID;

        // Fetch doctor info (assuming standard fields exist based on design, otherwise fallback)
        return [
            'name'       => get_the_title($author_id),
            'avatar_url' => get_the_post_thumbnail_url($author_id, 'medium'),
            'spec'       => get_field('doctor_specialty', $author_id) ?: 'Специалист',
            'verified'   => get_field('doc_verified', $author_id) ?: false,
            'tags'       => get_field('doc_tags', $author_id) ?: [],
            'stats'      => get_field('doc_stats', $author_id) ?: [],
            'bio'        => get_field('doctor_desc', $author_id) ?: get_post_field('post_content', $author_id),
            'quote'      => get_field('doc_quote', $author_id) ?: '',
        ];
    }

    protected function getReviewsData($id)
    {
        $reviews_field = get_field('reviews', $id);
        if (!$reviews_field || empty($reviews_field)) {
            return [];
        }

        return is_array($reviews_field) ? $reviews_field : [$reviews_field];
    }

    protected function getRecommendations($post_id)
    {
        $related_diseases = get_field('related_diseases', $post_id) ?: [];
        $disease_ids = wp_list_pluck($related_diseases, 'ID');
        
        // If the book doesn't have related diseases, we might still want to show some generic fallback
        // But for now let's query for related.
        
        // Find categories for these diseases to broaden the search
        $category_ids = [];
        if (!empty($disease_ids)) {
            foreach ($disease_ids as $did) {
                $terms = wp_get_post_terms($did, 'disease_category', ['fields' => 'ids']);
                if (!is_wp_error($terms)) {
                    $category_ids = array_merge($category_ids, $terms);
                }
            }
        }
        $category_ids = array_unique($category_ids);
        
        // Get all diseases in these categories
        $target_disease_ids = $disease_ids;
        if (!empty($category_ids)) {
            $cat_diseases = get_posts([
                'post_type' => 'disease',
                'posts_per_page' => -1,
                'fields' => 'ids',
                'tax_query' => [
                    [
                        'taxonomy' => 'disease_category',
                        'field' => 'term_id',
                        'terms' => $category_ids,
                    ]
                ]
            ]);
            $target_disease_ids = array_unique(array_merge($target_disease_ids, $cat_diseases));
        }

        if (empty($target_disease_ids)) {
            return []; // or fallback query
        }

        // Now query books and courses that link to any of these diseases
        // Since 'related_diseases' is a serialized array of IDs, we use multiple LIKE queries
        $meta_query = ['relation' => 'OR'];
        foreach ($target_disease_ids as $tdid) {
            $meta_query[] = [
                'key' => 'related_diseases',
                'value' => '"' . $tdid . '"',
                'compare' => 'LIKE'
            ];
        }

        $query_args = [
            'post_type' => ['book', 'course'],
            'post__not_in' => [$post_id],
            'posts_per_page' => 4,
            'meta_query' => $meta_query,
            'orderby' => 'rand'
        ];

        $recs = get_posts($query_args);
        
        $rec_data = [];
        foreach ($recs as $rec) {
            $type_label = $rec->post_type === 'course' ? 'Курс' : 'Книга';
            $features   = $rec->post_type === 'book'
                ? (get_field('book_features', $rec->ID) ?: [])
                : (get_field('benefits', $rec->ID) ?: []);
            $delivery   = $rec->post_type === 'book'
                ? (get_field('book_delivery_note', $rec->ID) ?: '')
                : (get_field('ps_delivery_method', $rec->ID) ?: '');

            $rec_data[] = [
                'id'         => $rec->ID,
                'title'      => get_the_title($rec->ID),
                'url'        => get_permalink($rec->ID),
                'type_label' => $type_label,
                'features'   => array_slice($features, 0, 3),
                'price'      => get_field('price', $rec->ID) ?: '',
                'price_old'  => get_field('book_price_old', $rec->ID) ?: get_field('ps_price_old', $rec->ID) ?: '',
                'delivery'   => $delivery,
                'image'      => get_the_post_thumbnail_url($rec->ID, 'medium') ?: '',
                'badge'      => get_field('ps_card_badge', $rec->ID) ?: '',
            ];
        }

        return $rec_data;
    }
}
