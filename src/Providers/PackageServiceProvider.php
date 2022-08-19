<?php

namespace Larapress\Sitemap\Providers;

use Illuminate\Support\ServiceProvider;
use Larapress\Sitemap\Commands\BuildSitemap;
use Larapress\Sitemap\Services\Sitemap\ISitemapService;
use Larapress\Sitemap\Services\Sitemap\SitemapService;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ISitemapService::class, SitemapService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'larapress');
        $this->publishes([
            __DIR__.'/../../config/sitemap.php' => config_path('larapress/sitemap.php'),
        ], ['config', 'larapress', 'larapress-sitemap']);


        if ($this->app->runningInConsole()) {
            $this->commands([
                BuildSitemap::class,
            ]);
        }
    }
}
