<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';
    protected $adminNamespace = 'App\Http\Controllers\Admin';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapAdminRoutes();

        $this->mapWebRoutes();

        $this->mapCpRoutes();

        $this->mapCronRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        switch(request()->getHttpHost()){
            case 'usa.limak.az':
                $route = 'routes/usa.php';
                break;
            case 'trlimak.kulis.az':
                $route = 'routes/tr.php';
                break;
            case 'tr.limak.az':
                $route = 'routes/tr.php';
                break;
            default :
                $route = 'routes/web.php';
                break;
        }
        Route::middleware('web')
            ->prefix(LaravelLocalization::setLocale().'/')
            ->namespace($this->namespace)
            ->group(base_path($route));
    }

    protected function mapAdminRoutes()
    {
        Route::prefix(LaravelLocalization::setLocale().'/admin')
            ->middleware('admin')
            ->namespace($this->namespace)
            ->group(base_path('routes/admin.php'));
    }

    protected function mapCpRoutes()
    {
        Route::prefix(LaravelLocalization::setLocale().'/cp')
            ->middleware('admin')
            ->namespace($this->namespace)
            ->group(base_path('routes/cp.php'));
    }

    protected function mapCronRoutes()
    {
        Route::prefix(LaravelLocalization::setLocale().'/cron')
            //->middleware('cron')
            ->namespace($this->namespace)
            ->group(base_path('routes/cron.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
