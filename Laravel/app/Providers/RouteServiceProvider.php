<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->mapWebRoutes();
        $this->configureRateLimiting();
        $this->routes(function () {
            Route::middleware('web')
                ->namespace($this->namespace . '\web')
                ->group(base_path('routes/web.php'));

            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            $this->mapModulesRoutes();
        });
    }

    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60);
        });
    }

    protected function mapModulesRoutes()
    {
        $moduleFiles = File::files(base_path('routes/modules'));
        $customRoutes = [];
        foreach ($moduleFiles as $path) {
            $file = pathinfo($path);
            if ($file['extension'] == 'php') {
                array_push($customRoutes, $file['filename']);
            }
        }
        foreach ($customRoutes as $route) {
            Route::prefix('api/modules/' . $route)
                ->middleware('api')
                ->namespace(sprintf('%s\modules\%s', $this->namespace, $route))
                ->group(base_path(sprintf('routes/modules/%s.php', $route)));
        }
    }
}
