<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use App\Models\User;
use App\Observers\UserObserver;

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
        if (
            app()->environment('production') ||
            str_starts_with((string) config('app.url'), 'https://') ||
            request()->server('HTTP_X_FORWARDED_PROTO') === 'https'
        ) {
            URL::forceScheme('https');
        }

        Schema::defaultStringLength(191);
        Paginator::useBootstrapFive();
        
        // Register the UserObserver to automatically create a profile when a user is created
        User::observe(UserObserver::class);
    }
}
