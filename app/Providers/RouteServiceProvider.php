<?php

namespace App\Providers;

use Hotash\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            foreach (config('tenancy.central_domains', []) as $domain) {
                Route::domain($domain)
                    ->prefix('api')
                    ->middleware('api')
                    ->group(base_path('routes/api.php'));

                Route::domain($domain)
                    ->middleware('web')
                    ->group(base_path('routes/web.php'));
            }
            Route::middleware(['web', 'universal', InitializeTenancyByDomainOrSubdomain::class])
                ->group(base_path('routes/universal.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
