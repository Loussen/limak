<?php
/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/3/18
 * Time: 11:48 PM.
 */

namespace KgBot\LaravelLocalization;

use Illuminate\Support\ServiceProvider;
use KgBot\LaravelLocalization\Classes\ExportLocalizations;
use KgBot\LaravelLocalization\Console\Commands\ExportMessages;
use KgBot\LaravelLocalization\Console\Commands\ExportMessagesToFlat;

class LaravelLocalizationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->bind('export-localization', function () {
            $phpRegex = config('laravel-localization.file_regexp.php', '/^.+\.php$/i');
            $jsonRegex = config('laravel-localization.file_regexp.json', '/^.+\.json$/i');

            return new ExportLocalizations($phpRegex, $jsonRegex);
        });

        /*
         * Config
         */
        $this->mergeConfigFrom(
            __DIR__.'/config/laravel-localization.php', 'laravel-localization'
        );

        $this->publishes([
            __DIR__.'/config/laravel-localization.php' => config_path('laravel-localization.php'),
        ], 'config');

        /*
         * Routes
         */
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ExportMessages::class,
                ExportMessagesToFlat::class,
            ]);
        }
    }
}
