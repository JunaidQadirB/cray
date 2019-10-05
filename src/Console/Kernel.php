<?php

namespace MoonBear\LaravelCrudScaffold\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        "\MoonBear\Make\Console\Commands\ControllerMakeCommand::class",
        "\MoonBear\Make\Console\Commands\FactoryMakeCommand::class",
        "\MoonBear\Make\Console\Commands\GeneratorCommand::class",
        "\MoonBear\Make\Console\Commands\MakeScaffold::class",
        "\MoonBear\Make\Console\Commands\MigrateMakeCommand::class",
        "\MoonBear\Make\Console\Commands\ModelMakeCommand::class",
        "\MoonBear\Make\Console\Commands\RequestMakeCommand::class",
        "\MoonBear\Make\Console\Commands\ViewMakeCommand::class",
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
    }
}
