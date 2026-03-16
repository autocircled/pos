<?php

namespace App\Providers;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Apply timezone from settings (for reports and all date/time display)
        if (Schema::hasTable('settings')) {
            $timezone = Setting::get('timezone', 'Asia/Dhaka');
            if ($timezone && in_array($timezone, timezone_identifiers_list(), true)) {
                config(['app.timezone' => $timezone]);
                date_default_timezone_set($timezone);
                Carbon::setLocale('en');
            }
        }

        // Use Bootstrap 5 pagination
        Paginator::useBootstrapFive();

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
