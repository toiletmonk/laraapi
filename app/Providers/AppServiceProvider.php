<?php

namespace App\Providers;


use App\Http\Middleware\CheckTokenExpiration;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Termwind\Components\Li;

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
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip());
        });
        RateLimiter::for('checkout', function (Request $request) {
            return $request->user()
                ? Limit::perMinute(60)->by($request->user()->id())
                : Limit::perMinute(10)->by($request->ip());
        });
        $kernel = $this->app->make(\Illuminate\Contracts\Http\Kernel::class);
        $kernel->appendMiddlewareToGroup('api', CheckTokenExpiration::class);
    }
}
