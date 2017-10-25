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
       //$schedule->call(function() {
       //    $admin = new \App\User();
       //    $admin->name = "test";
       //    $admin->email = gmdate('Y-m-d H:i:s') . rand(0, 1000000);
       //    $admin->password = 'test';
       //    $admin->save();
       //})->everyMinute();

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
    }
}
