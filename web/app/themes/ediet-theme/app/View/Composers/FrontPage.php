<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class FrontPage extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'front-page',
    ];

    /**
     * Data to be passed to view before rendering, but after merging.
     *
     * @return array
     */
    public function with()
    {
        return [
            'home_blocks' => $this->getHomeBlocks(),
        ];
    }

    /**
     * Fetch and normalize flexible content blocks
     *
     * @return array
     */
    public function getHomeBlocks()
    {
        $blocks = get_field('home_blocks');
        return is_array($blocks) ? $blocks : [];
    }
}
