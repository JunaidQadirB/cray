<?php

namespace JunaidQadirB\Cray;

use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Support\ServiceProvider;
use JunaidQadirB\Cray\Console\Commands\ControllerMakeCommand;
use JunaidQadirB\Cray\Console\Commands\Cray;
use JunaidQadirB\Cray\Console\Commands\DeleteResourceCommand;
use JunaidQadirB\Cray\Console\Commands\FactoryMakeCommand;
use JunaidQadirB\Cray\Console\Commands\MigrateMakeCommand;
use JunaidQadirB\Cray\Console\Commands\ModelMakeCommand;
use JunaidQadirB\Cray\Console\Commands\RequestMakeCommand;
use JunaidQadirB\Cray\Console\Commands\ViewMakeCommand;

class CrayServiceProvider extends ServiceProvider
{
    protected $commands = [
        ControllerMakeCommand::class,
        FactoryMakeCommand::class,
        Cray::class,
        MigrateMakeCommand::class,
        ModelMakeCommand::class,
        RequestMakeCommand::class,
        ViewMakeCommand::class,
        DeleteResourceCommand::class
    ];

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {

        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'cray');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'cray');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

//        if ($this->app->runningInConsole()) {
        $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('cray.php'),
            ], 'cray');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/cray'),
        ], 'views');*/

        // Publishing assets.
        $this->publishes([
                __DIR__ . '/../resources/stubs' => resource_path('stubs')
            ], 'cray');

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/cray'),
        ], 'lang');*/

        $this->app->when(MigrationCreator::class)
            ->needs('$customStubPath')
            ->give(function ($app) {
                return resource_path('stubs');
            });

        // Registering package commands.
        $this->commands($this->commands);
//        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'cray');

        // Register the main class to use with the facade
        $this->app->singleton('cray', function (Container $container) {
            return new Cray($container->get('files'));
        });
    }
}
