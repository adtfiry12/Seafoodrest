<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\URL;

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
        if (request()->header('x-forwarded-proto') === 'https' || request()->server('HTTP_X_FORWARDED_PROTO') === 'https') {
            URL::forceScheme('https');
        }
        RateLimiter::for('login', function (Request $request): array {
            $email = Str::lower((string) $request->input('email'));

            return [
                Limit::perMinute(5)->by($email.'|'.$request->ip()),
                Limit::perMinute(20)->by($request->ip()),
            ];
        });

        // Anti-DoS Register Flooding (Bcrypt CPU exhaustion protection)
        RateLimiter::for('register', function (Request $request): array {
            return [
                Limit::perMinute(5)->by($request->ip()),
            ];
        });

        // Anti-Spam Checkout / Stock Hoarding
        RateLimiter::for('checkout', function (Request $request): array {
            $userKey = $request->user()?->id ?: $request->ip();

            return [
                Limit::perMinute(10)->by($userKey),
            ];
        });

        // Anti-DoS File Upload
        RateLimiter::for('uploads', function (Request $request): array {
            $userKey = $request->user()?->id ?: $request->ip();

            return [
                Limit::perMinute(10)->by($userKey),
            ];
        });

        // Anti-Brute Force Password Update
        RateLimiter::for('password_update', function (Request $request): array {
            return [
                Limit::perMinute(5)->by($request->user()?->id ?: $request->ip()),
            ];
        });
    }
}
