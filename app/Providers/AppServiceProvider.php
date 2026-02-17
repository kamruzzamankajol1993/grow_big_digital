<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use App\Models\SiteConfig;
use App\Models\SocialLink;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        

       
        /**
         * Share data with all views safely.
         * Using Schema::hasTable prevents errors during initial migrations.
         */
        View::composer('*', function ($view) {
            
            // 1. Fetch Site Configuration safely
            $siteConfig = null;
            if (Schema::hasTable('site_configs')) {
                // If no record exists, we create an empty object to prevent "property of non-object" errors
                $siteConfig = SiteConfig::first() ?? new SiteConfig();
            }
            $view->with('siteConfig', $siteConfig);

            // 2. Fetch Social Links safely
            $social_links = collect();
            if (Schema::hasTable('social_links')) {
                $social_links = SocialLink::all();
            }
            $view->with('social_links', $social_links);

          
        });
    }
}