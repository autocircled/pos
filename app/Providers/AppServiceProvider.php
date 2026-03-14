<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Share currency symbol globally with all views
        View::composer('*', function ($view) {
            if (Schema::hasTable('settings')) {
                $view->with('currency', Setting::get('currency_symbol', '৳'));
            } else {
                $view->with('currency', '৳');
            }
        });

        // Create a Blade directive for currency formatting
        Blade::directive('currency', function ($amount) {
            return "<?php echo (Schema::hasTable('settings') ? App\Models\Setting::get('currency_symbol', '৳') : '৳') . number_format($amount, 2); ?>";
        });
    }
}
