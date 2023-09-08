<?php

namespace JunaidQadirB\Cray\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use JunaidQadirB\Cray\Console\Commands\ControllerMakeCommand;
use JunaidQadirB\Cray\Console\Commands\Cray;
use JunaidQadirB\Cray\Console\Commands\FactoryMakeCommand;
use JunaidQadirB\Cray\Console\Commands\MigrateMakeCommand;
use JunaidQadirB\Cray\Console\Commands\ModelMakeCommand;
use JunaidQadirB\Cray\Console\Commands\RequestMakeCommand;
use JunaidQadirB\Cray\Console\Commands\ViewMakeCommand;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ControllerMakeCommand::class,
        FactoryMakeCommand::class,
        Cray::class,
        MigrateMakeCommand::class,
        ModelMakeCommand::class,
        RequestMakeCommand::class,
        ViewMakeCommand::class,
    ];

    /**
     * Define the application's command schedule.
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
        $this->load(__DIR__.'/Commands');
    }
}
