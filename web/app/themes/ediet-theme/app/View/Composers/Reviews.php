<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Reviews extends Composer
{
    protected static $views = ['template-reviews'];

    public function with(): array
    {
        return [
            'reviews'         => $this->getReviews(),
            'ratings_summary' => $this->getRatingsSummary(),
        ];
    }

    private function getReviews(): array
    {
        $query = new \WP_Query([
            'post_type'      => 'review',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);

        return array_map(function ($post) {
            // ACF fields per group_review.json:
            // type (select): text | image | video | before_after
            // before_text (wysiwyg): main text or "before" text
            // after_text  (wysiwyg): "after" text (conditional on before_after)
            // media (image): screenshot / photo

            $type        = get_field('type', $post->ID) ?: 'text';
            $before_text = get_field('before_text', $post->ID) ?: $post->post_content;
            $after_text  = get_field('after_text', $post->ID) ?: '';
            $media       = get_field('media', $post->ID);
            $media_url   = is_array($media) ? ($media['url'] ?? '') : ($media ?: '');

            // Normalise type to composer's internal types
            // ACF uses 'image' but template uses 'photo'
            if ($type === 'image') {
                $type = 'photo';
            }

            // Auto-promote to photo if media present but type is still text
            if ($type === 'text' && $media_url) {
                $type = 'photo';
            }

            // Author info from post (no dedicated ACF author fields in this group)
            $author_name = $post->post_title;
            $avatar_url  = get_the_post_thumbnail_url($post->ID, 'thumbnail') ?: '';

            return [
                'id'          => $post->ID,
                'type'        => $type,
                'rating'      => 5, // no rating field in ACF group; default to 5
                'text'        => $before_text,
                'author'      => $author_name,
                'avatar_url'  => $avatar_url,
                'image_url'   => $media_url,
                'before_text' => $before_text,
                'after_text'  => $after_text,
                'before_img'  => ($type === 'before_after') ? '' : '',
                'after_img'   => '',
                'date'        => get_the_date('d.m.Y', $post),
            ];
        }, $query->posts);
    }

    private function getRatingsSummary(): array
    {
        $reviews = $this->getReviews();
        if (empty($reviews)) {
            return ['avg' => 0, 'total' => 0, 'by_star' => []];
        }

        $total   = count($reviews);
        $sum     = array_sum(array_column($reviews, 'rating'));
        $by_star = array_fill(1, 5, 0);

        foreach ($reviews as $r) {
            $star = max(1, min(5, (int) $r['rating']));
            $by_star[$star]++;
        }

        return [
            'avg'     => round($sum / $total, 1),
            'total'   => $total,
            'by_star' => $by_star,
        ];
    }
}
