<?php

namespace JunaidQadirB\Cray\Console;

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
        "\JunaidQadirB\Make\Console\Commands\ControllerMakeCommand::class",
        "\JunaidQadirB\Make\Console\Commands\FactoryMakeCommand::class",
        "\JunaidQadirB\Make\Console\Commands\GeneratorCommand::class",
        "\JunaidQadirB\Make\Console\Commands\MakeScaffold::class",
        "\JunaidQadirB\Make\Console\Commands\MigrateMakeCommand::class",
        "\JunaidQadirB\Make\Console\Commands\ModelMakeCommand::class",
        "\JunaidQadirB\Make\Console\Commands\RequestMakeCommand::class",
        "\JunaidQadirB\Make\Console\Commands\ViewMakeCommand::class",
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
