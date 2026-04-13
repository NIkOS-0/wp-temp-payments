<?php

namespace App\Providers;

use Roots\Acorn\Sage\SageServiceProvider;

class ThemeServiceProvider extends SageServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        
        add_filter('acf/settings/save_json', function() {
            return get_stylesheet_directory() . '/acf-json';
        });

        add_filter('acf/settings/load_json', function($paths) {
            $paths[] = get_stylesheet_directory() . '/acf-json';
            return $paths;
        });
    }
}
