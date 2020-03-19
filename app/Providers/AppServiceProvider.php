<?php

namespace App\Providers;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use KgBot\LaravelLocalization\Facades\ExportLocalizations;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {

        if(env('APP_ENV') === 'prod')
        {
            $url->forceScheme('https');
        }
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        View::composer( ['tr.layouts.admin_anbar_tr','front.new.panel.index','front.panel.index', 'front.orders.link.index','admin.courier.index','usa.layouts.admin_anbar'], function ( $view ) {

            return $view->with( [
                'message' => ExportLocalizations::export()->toArray(),
            ] );
        } );
    }
}
