<?php

namespace JunaidQadirB\Cray;

use Illuminate\Support\Facades\Blade;
use JunaidQadirB\Cray\Console\Commands\ControllerMakeCommand;
use JunaidQadirB\Cray\Console\Commands\FactoryMakeCommand;
use JunaidQadirB\Cray\Console\Commands\MakeScaffold;
use JunaidQadirB\Cray\Console\Commands\MigrateMakeCommand;
use JunaidQadirB\Cray\Console\Commands\ModelMakeCommand;
use JunaidQadirB\Cray\Console\Commands\RequestMakeCommand;
use JunaidQadirB\Cray\Console\Commands\ViewMakeCommand;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    protected $commands = [
        ControllerMakeCommand::class,
        FactoryMakeCommand::class,
        MakeScaffold::class,
        MigrateMakeCommand::class,
        ModelMakeCommand::class,
        RequestMakeCommand::class,
        ViewMakeCommand::class,
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/resources/stubs' => resource_path('stubs')], 'cray');

        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
//        Blade::component('components.themes.default.modal', 'modal');
    }
}
