<?php

namespace App\Providers;

use App\Services\SystemSettings;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
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
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        Schema::defaultStringLength(191);

        $systemSettings = SystemSettings::load();
        $systemColors = array_intersect_key($systemSettings, SystemSettings::defaultColors());

        View::share('systemSettings', $systemSettings);
        View::share('systemColors', $systemColors);

        View::composer('components.footer', function ($view) {
            $footerCategories = Cache::remember('footer_categories', 86400, function () {
                return Category::withCount(['products' => function ($query) {
                    $query->where('stock', '>', 0);
                }])->whereNull('seller_id')->orderByDesc('products_count')->orderBy('name')->take(5)->get();
            });
            $view->with('footerCategories', $footerCategories);
        });
    }
}
