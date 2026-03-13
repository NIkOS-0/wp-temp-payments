<?php
require_once('web/wp/wp-load.php');

$dashboard_page = get_page_by_path('dashboard');
if ($dashboard_page) {
    echo "ID: " . $dashboard_page->ID . "\n";
    echo "Title: " . $dashboard_page->post_title . "\n";
    $template = get_post_meta($dashboard_page->ID, '_wp_page_template', true);
    echo "Template: " . $template . "\n";
} else {
    echo "Dashboard page not found by path 'dashboard'\n";
}

$pages = get_posts(['post_type' => 'page', 'posts_per_page' => -1]);
foreach ($pages as $p) {
    $t = get_post_meta($p->ID, '_wp_page_template', true);
    echo "Page: " . $p->post_title . " | Slug: " . $p->post_name . " | Template: " . $t . "\n";
}
