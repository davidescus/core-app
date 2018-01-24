<?php

namespace App\Console;

use App\Console\Commands\DistributionPublish;
use App\Console\Commands\DistributionEmailSchedule;
use App\Console\Commands\ImportNewEvents;
use App\Console\Commands\SetResultAndStatus;
use App\Console\Commands\PublishArchives;
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
        DistributionPublish::class,
        DistributionEmailSchedule::class,
        ImportNewEvents::class,
        SetResultAndStatus::class,
        PublishArchives::class,
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

        // send for email_schedule each minute
        $schedule->call(function() {
            new \App\Http\Controllers\Cron\SendMail();
        })->everyMinute();
    }
}
