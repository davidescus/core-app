<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\Controller;

// this will process all days subscriptions
// at end of day (23.59)
class ProcessDaysSubscription extends Controller
{
        $currentDate = gmdate('Y-m-d');

        // get all days subscriptions
        $subscriptions = \App\Subscription::where('status', 'active')
            ->where('type', 'days')->get();

        foreach ($subscriptions as $subscription) {

            // 
        }
}

