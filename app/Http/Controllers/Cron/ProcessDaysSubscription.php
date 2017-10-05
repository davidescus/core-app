<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\Controller;

// this will process all days subscriptions
// at end of day (23.59)
class ProcessDaysSubscription extends Controller
{

    public function __construct()
    {
        $currentDate = gmdate('Y-m-d');

        // get all active subscriptions type days
        $subscriptions = \App\Subscription::where('status', 'active')
            ->where('type', 'days')->get();

        foreach ($subscriptions as $subscription) {

            // get number of subscriptionTipHistory events on systemDate === today GMT
            $countEvents = \App\SubscriptionTipHistory::where('systemDate', $currentDate)
                ->where('subscriptionId', $subscription->id)->count();

            // add noTip event if user not receive any event today
            if (! $countEvents)
                $this->addNoTipToSubscriptionTipHistory($subscription, $currentDate);


            // get suubscriptionTipHistory events systemDate === today GMT
            $events = \App\SubscriptionTipHistory::where('systemDate', $currentDate)
                ->where('subscriptionId', $subscription->id)->get();

            // if events already processSubscription continue
            if ($events[0]->processSubscription)
                continue;

            // vip subscriptions not care about results
            // also noTip not give them one day
            if ($subscription->isVip) {
                foreach ($events as $event) {
                    $event->processSubscription = '1';
                    $event->processType = '0';
                    $event->update();
                }
                $this->archiveSubscription($subscription, $currentDate);
                continue;
            }

            // check events status
            $haveNoTip = false;
            $haveWinLossDraw = false;
            $allEventsFinished = true;
            foreach ($events as $event) {

                if ($event->isNoTip)
                   $haveNoTip = true;

                if ((int) $event->statusId === 1 || (int) $event->statusId === 2 || (int) $event->statusId === 3)
                    $haveWinLossDraw = true;

                if (trim($event->statusId) === '' && !$event->isNoTip)
                    $allEventsFinished = false;
            }

            // process on noTip
            if ($haveNoTip) {
                foreach ($events as $event) {
                    $event->processSubscription = '1';
                    $event->processType = '+1';
                    $event->update();
                }

                // add one day to subscription
                $subscription->dateEnd = date('Y-m-d', strtotime('+1day', strtotime($subscription->dateEnd)));
                $subscription->update();
                continue;
            }

            // process on win loss draw
            if ($haveWinLossDraw) {
                foreach ($events as $event) {
                    $event->processSubscription = '1';
                    $event->processType = '0';
                    $event->update();
                }

                $this->archiveSubscription($subscription, $currentDate);
                continue;
            }

            // there is events and all finished
            // there is no win, loss, draw
            // there is no noTip
            // only postp - add one day
            if ($allEventsFinished) {
                foreach ($events as $event) {
                    $event->processSubscription = '1';
                    $event->processType = '+1';
                    $event->update();
                }

                // add one day to subscription
                $subscription->dateEnd = date('Y-m-d', strtotime('+1day', strtotime($subscription->dateEnd)));
                $subscription->update();
                continue;
            }
        }
    }

    // @param \App\Subscription $subscription
    // @param string $systemDate
    // this will add an noTip event
    // @return void
    private function addNoTipToSubscriptionTipHistory($subscription, $systemDate)
    {
        \App\SubscriptionTipHistory::create([
            'subscriptionId' => $subscription->id,
            'customerId' => $subscription->customerId,
            'siteId' => $subscription->siteId,
            'isCustom' => $subscription->isCustom,
            'type' => $subscription->type,
            'isNoTip' => '1',
            'isVip' => $subscription->isVip,
            'systemDate' => $systemDate,
        ]);
    }

    // @param \App\Subscription $subscription
    // @param $date
    // this will archive a subscription
    // if endDate is greater or equal to $date
    // @return void
    private function archiveSubscription($subscription, $date)
    {
        if (strtotime($subscription->dateEnd) <= strtotime($date)) {
            $subscription->status = 'archived';
            $subscription->update();
        }
    }
}

