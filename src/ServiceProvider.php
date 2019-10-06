<?php

namespace MoonBear\LaravelCrudScaffold;

use Illuminate\Support\Facades\Blade;
use MoonBear\LaravelCrudScaffold\Console\Commands\ControllerMakeCommand;
use MoonBear\LaravelCrudScaffold\Console\Commands\FactoryMakeCommand;
use MoonBear\LaravelCrudScaffold\Console\Commands\MakeScaffold;
use MoonBear\LaravelCrudScaffold\Console\Commands\MigrateMakeCommand;
use MoonBear\LaravelCrudScaffold\Console\Commands\ModelMakeCommand;
use MoonBear\LaravelCrudScaffold\Console\Commands\RequestMakeCommand;
use MoonBear\LaravelCrudScaffold\Console\Commands\ViewMakeCommand;

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
        $this->publishes([__DIR__ . '/resources/stubs' => resource_path('stubs')], 'laravel-crud-scaffold');

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
