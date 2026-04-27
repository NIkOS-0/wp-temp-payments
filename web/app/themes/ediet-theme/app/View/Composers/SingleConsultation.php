<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class SingleConsultation extends Composer
{
    protected static $views = [
        'single-consultation',
    ];

    public function with()
    {
        $id = get_the_ID();

        return [
            'service'     => $this->getConsultationData($id),
            'author'      => $this->getAuthorData($id),
            'reviews'     => $this->getReviewsData($id),
            'cross_sells' => $this->getRecommendations($id),
        ];
    }

    protected function getConsultationData($id)
    {
        $benefits = get_field('benefits', $id);
        if (!empty($benefits) && is_array($benefits)) {
            $features = $benefits;
        } else {
            $features = [
                ['text' => 'Онлайн-встреча в удобном мессенджере'],
                ['text' => 'Полный анализ симптомов и назначений'],
                ['text' => 'Пошаговый план лечения на 3 месяца'],
            ];
        }

        $top_labels_raw = get_field('ps_top_labels', $id) ?: [['text' => 'Консультация', 'style' => 'default']];
        $top_labels = array_map([$this, 'normalizeLabel'], $top_labels_raw);

        return [
            'title'          => get_the_title($id),
            'top_labels'     => $top_labels,
            'image_badge'    => get_field('ps_card_badge', $id) ?: '',
            'subtitle'       => get_field('ps_subtitle', $id) ?: 'Индивидуальная консультация',
            'stats'          => get_field('ps_stats', $id) ?: [],
            'features'       => $features,
            'price'          => get_field('price', $id) ?: '',
            'price_old'      => get_field('ps_price_old', $id) ?: '',
            'delivery_note'  => get_field('ps_delivery_method', $id) ?: 'Zoom / Telegram / Skype',
            'description'    => get_field('ps_description', $id) ?: [],
            'contents'       => [],
            'gallery'        => get_field('ps_gallery', $id) ?: [],
            'pricing_tiers'  => get_field('ps_pricing_tiers', $id) ?: [],
        ];
    }

    /**
     * Normalize a label so the view can always render with hex colors.
     * Falls back to the legacy `style` preset when bg/text colors are empty.
     */
    protected function normalizeLabel($label)
    {
        $presets = [
            'default'  => ['bg' => '#FDE8D4', 'fg' => '#B85E30'],
            'verified' => ['bg' => '#DCFCE7', 'fg' => '#15803D'],
            'pdf'      => ['bg' => '#FAD0AA', 'fg' => '#3A2418'],
            'dark'     => ['bg' => '#2A1A10', 'fg' => '#F5EFE2'],
        ];

        $style = $label['style'] ?? 'default';
        $defaults = $presets[$style] ?? $presets['default'];

        $bg = !empty($label['bg_color']) ? $label['bg_color'] : $defaults['bg'];
        $fg = !empty($label['text_color']) ? $label['text_color'] : $defaults['fg'];

        return [
            'text'       => $label['text'] ?? '',
            'bg_color'   => $bg,
            'text_color' => $fg,
            'style'      => $style,
        ];
    }

    protected function getAuthorData($id)
    {
        $doc = get_field('consultation_doctor', $id) ?: get_field('service_doctor', $id);
        if (!$doc || empty($doc)) {
            return null;
        }

        $author = is_array($doc) ? $doc[0] : $doc;
        $author_id = $author->ID;

        return [
            'name'         => get_the_title($author_id),
            'avatar_url'   => get_the_post_thumbnail_url($author_id, 'medium'),
            'spec'         => get_field('doc_specialty', $author_id) ?: 'Специалист',
            'verified'     => get_field('doc_verified', $author_id) ?: true,
            'tags'         => get_field('doc_tags', $author_id) ?: [],
            'stats'        => get_field('doc_stats', $author_id) ?: [],
            'bio'          => get_field('doctor_desc', $author_id) ?: get_post_field('post_content', $author_id),
            'quote'        => get_field('doc_quote', $author_id) ?: '',
            'section_heading' => get_field('consultation_author_heading', $id) ?: 'Ведёт ваш <em>врач-нутрициолог</em>',
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
            'post_type' => ['course', 'book'],
            'posts_per_page' => 4,
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
