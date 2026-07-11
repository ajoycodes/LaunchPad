<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

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
        // The app loads Bootstrap 5, not Tailwind — Laravel's default
        // pagination view uses Tailwind utility classes that do nothing
        // here, leaving its prev/next SVGs completely unsized (they were
        // rendering at the browser's ~300x150px default for unsized SVGs).
        Paginator::useBootstrapFive();
    }
}
