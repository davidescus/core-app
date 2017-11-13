<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // process day subscriptions at end of day
        //   - if no event add noTip
        //   - archive subscriptions
        //   - activate waiting subscriptions
        //   - set package section
        $schedule->call(function() {
            new \App\Http\Controllers\Cron\ProcessDaysSubscription();
        })->timezone('GMT')->dailyAt('00:01');

        // get new events from portal at every 5 minutes.
        $schedule->call(function() {
            new \App\Http\Controllers\Cron\PortalNewEvents();
        })->everyFiveMinutes();

        // send for email_schedule each minute
        $schedule->call(function() {
            new \App\Http\Controllers\Cron\SendMail();
        })->everyMinute();
    }
}
