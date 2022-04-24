<?php

namespace AhmetBarut\FullSearch\Providers;

use AhmetBarut\FullSearch\Components\FullSearchComponent;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class FullSearchServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../../views', 'fullsearch');
        Blade::component(FullSearchComponent::class, 'fullsearch');

        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');

        $this->publishes([
            __DIR__ . '/../../config/fullsearch.php' => config_path('fullsearch.php'),
        ], 'fullsearch-config');

        $this->publishes([
            __DIR__ . '/../../views' => resource_path('views/vendor/fullsearch'),
        ], 'fullsearch-views');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
