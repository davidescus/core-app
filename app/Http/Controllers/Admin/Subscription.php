<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Subscription extends Controller
{

    // get all subscriptions
    // @return array()
    public function index() {
        $subscriptions = \App\Subscription::all()->toArray();

        foreach ($subscriptions as $k => $v) {
            $customerEmail = \App\Customer::find($v['customerId'])->email;
            $siteName = \App\Site::find($v['siteId'])->name;
            $subscriptions[$k]['customerEmail'] = $customerEmail;
            $subscriptions[$k]['siteName'] = $siteName;
        }
        return $subscriptions;
    }

    public function get() {}

    // Subscription
    // @param integer $packageId
    // @param string  $name
    // @param integer $subscription
    // @param integer $price
    // @param string  $type days | tips
    // @param string  $dateStart (only for "days" format Y-m-d)
    // @param string  $dateEnd   (only for "days" format Y-m-d)
    // @param string  $customerEmail
    // store new subscription automatic detect if is custom or not
    //  - compare values with original package.
    // @return array()
    public function store(Request $r) {

        $packageId = $r->input('packageId');
        $name = $r->input('name');
        $subscription = $r->input('subscription');
        $price = $r->input('price');
        $type = $r->input('type');
        $dateStart = $r->input('dateStart');
        $dateEnd = $r->input('dateEnd');
        $customerEmail = $r->input('customerEmail');
        $status = 'active';

        // check if package exist
        $package = \App\Package::find($packageId);
        if (!$packageId)
            return [
                'type' => 'error',
                'mesasge' => 'Package not exist anymore'
            ];

        // get siteId
        $sitePackage = \App\SitePackage::where('packageId', $packageId)->first();
        $siteId = $sitePackage->siteId;

        // get customer
        $customer = \App\Customer::where('email', $customerEmail)->first();

        $data = [
            'name' => $name,
            'customerId' => $customer->id,
            'siteId' => $siteId,
            'packageId' => $package->id,
            'isVip' => $package->isVip,
            'type' => $type,
            'subscription' => $subscription,
            'dateStart' => $dateStart,
            'dateEnd' => $dateEnd,
            'status' => $status,
        ];

        if ($type === 'tips') {
            $data['tipsLeft'] = $subscription;
            unset($data['dateStart']);
            unset($data['dateEnd']);
        }

        // check if subscription is custom
        if ($package->name != $name || $package->subscription != $subscription || $package->price != $price)
            $data['isCustom'] = '1';

        // if user already have active subscription on same package
        // set status waiting
        if (\App\Subscription::where('customerId', $customer->id)
            ->where('packageId', $packageId)
            ->where('status', 'active')->count())
        {
            $data['status'] = 'waiting';
        }

        // create subscription
        $subscription = \App\Subscription::create($data);

        // move package (packages group in real users if is possible)
        $packageInstance = new \App\Http\Controllers\Admin\Package();
        $packageInstance->evaluateAndChangeSection($packageId);

        return [
            'type' => 'success',
            'message' => 'Subscription was created with success!',
            'data' => $subscription,
        ];
    }

    // @param $packageId
    // will return array with subscriptionIds who not have enough tips
    // in current date
    // @return array()
    public function getSubscriptionsIdsWithNotEnoughTips($packageId)
    {
        $subscriptions = [];

        $pack = \App\Package::find($packageId);

        // use this only for tips packages
        if ($pack->subscriptionType !== 'tips')
            return $subscriptions;

        // get package associated tips number
        $tipsNumber = \App\Distribution::where('packageId', $pack->id)
            ->where('systemDate', gmdate('Y-m-d'))->count();

        if ($tipsNumber < 1)
            return $subscriptions;

        //get only subscription with tipsLeft less or equal then tips number
        $subs = \App\Subscription::where('status', 'active')
            ->where('packageId', $pack->id)
            ->where('tipsLeft', '<=', $tipsNumber)->get();

        // also check tipsBlocked
        foreach ($subs as $s) {
            if (($s->tipsLeft - $s->tipsBlocked) < $tipsNumber) {
                $subscriptions[] = $s->id;
            }
        }

        return $subscriptions;
    }

    // integer $eventId
    public function processSubscriptions($eventId)
    {
        // get all subscriptions tips history associated with event
        $tipsHistory = \App\SubscriptionTipHistory::where('eventId', $eventId)->get();
        foreach ($tipsHistory as $tip) {

            $subscription = \App\Subscription::find($tip->subscriptionId);

            if ($tip->type === 'tips') {

                // if has already process subscriptions rollback
                // this will create the original context before the tip process subscription
                if ($tip->processSubscription) {
                    $this->rollbackSubscription($subscription, $tip->processType);
                    $this->unProcessSubscriptionTipHistory($tip);
                    $this->manageTipsSubscriptionStatus($subscription);
                }

                // win
                if ($tip->statusId == 1) {
                    $this->processSubscriptionTipHistory($tip, '-1');

                    if ($subscription->tipsBlocked > 0) {
                        $subscription->tipsBlocked--;
                        $subscription->update();
                    }

                    $this->manageTipsSubscriptionStatus($subscription);
                }

                // loss | draw
                if ($tip->statusId == 2 || $subscription->statusId == 3) {

                    // vip
                    if ($tip->isVip) {
                        $this->processSubscriptionTipHistory($tip, '0');

                        $subscription->tipsLeft++;
                        $subscription->tipsBlocked--;
                        $subscription->update();

                        $this->manageTipsSubscriptionStatus($subscription);
                    }
                    else {
                        $this->processSubscriptionTipHistory($tip, '-1');

                        if ($subscription->tipsBlocked > 0) {
                            $subscription->tipsBlocked--;
                            $subscription->update();
                        }

                        $this->manageTipsSubscriptionStatus($subscription);
                    }
                }

                // postp
                if ($tip->statusId == 4) {
                    $this->processSubscriptionTipHistory($tip, '0');

                    $subscription->tipsLeft++;
                    $subscription->tipsBlocked--;
                    $subscription->update();

                    $this->manageTipsSubscriptionStatus($subscription);
                }
            }

            if ($tip->type === 'days') {

                // vip subscriptions do not care about results
                if ($subscription->isVip)
                    continue;

                // only events with systemDate less than today
                if (strtotime($tip->systemDate) >= strtotime(gmdate('Y-m-d')))
                    continue;

                // get all events associated with subscription in that day
                $allEventsInDay = \App\SubscriptionTipHistory::where('subscriptionId', $subscription->id)
                    ->where('systemDate', $tip->systemDate)->get();

                // check events status
                $haveWinLossDraw = false;
                $allEventsFinished = true;
                foreach ($allEventsInDay as $event) {

                    if ((int) $event->statusId === 1 || (int) $event->statusId === 2 || (int) $event->statusId === 3)
                        $haveWinLossDraw = true;

                    if (trim($event->statusId) === '' && !$event->isNoTip)
                        $allEventsFinished = false;
                }

                // if has already process subscriptions rollback
                // this will create the original context before the tip | tips process subscription
                if ($tip->processSubscription) {

                    // if tip (group of tips) process subscription roll back
                    $this->rollbackSubscription($subscription, $tip->processType);
                    // set not process subscription to all events
                    $this->unProcessSubscriptionTipHistory($allEventsInDay);
                }

                // mark subscription tips hystory process on win loss draw
                if ($haveWinLossDraw) {
                    $this->processSubscriptionTipHistory($allEventsInDay, '0');
                    continue;
                }

                if (! $allEventsFinished)
                    continue;

                // there is events and all finished
                // there is no win, loss, draw
                // there is no noTip
                // only postp - create one day bonus subscription
                $s = new \App\Subscription();
                $s->parentId = $subscription->id;
                $s->name = $subscription->name;
                $s->subscription = '1';
                $s->type = $subscription->type;
                $s->customerId = $subscription->customerId;
                $s->status = 'waiting';

                // if user not have any more subscription on package activate this
                if (! \App\Subscription::where('customerId', $subscription->customerId)
                    ->where('packageId', $subscription->packageId)
                    ->where('status', 'active')->count())
                {
                    $s->status = 'active';
                    $s->dateStart = gmdate('Y-m-d');
                    $s->dateEnd = gmdate('Y-m-d');
                }

                $s->save();
                $this->processSubscriptionTipHistory($allEventsInDay, $s->id);
            }

            // Evaluate packages and change section if need
            $packageInstance = new \App\Http\Controllers\Admin\Package();
            $packageInstance->evaluateAndChangeSection($subscription->packageId);
        }
    }

    // @param obj $subscription \App\Http\controllers\Admin\Subscription
    // will evaluate and modyfi the subscription status
    // @return void
    public function manageTipsSubscriptionStatus($subscription)
    {
        $status = 'active';
        if ($subscription->tipsLeft < 1)
            $status = 'archived';

        if ($status != $subscription->status) {
            $subscription->status = $status;
            $subscription->update();
        }
    }

    // @param \App\Subscription $subscription
    // this will archive a subscription
    // if dateEnd is smaller than today
    // @return void
    private function manageDaysSubscriptionStatus($subscription, $date)
    {
        $status = 'active';
        if (strtotime($subscription->dateEnd) < strtotime(gmdate('Y-m-d')))
            $status = 'archived';

        if ($status != $subscription->status) {
            $subscription->status = $status;
            $subscription->update();
        }
    }

    // @param obj \App\SubscriptionTipHistory | array of objects
    // will mark processSubscription = 0 and processType = ''
    // @return void
    public function unProcessSubscriptionTipHistory($event)
    {
        if (method_exists($event, 'update')) {
            $event->processSubscription = 0;
            $event->processType = '';
            $event->update();
            return;
        }

        foreach ($event as $tip) {
            $tip->processSubscription = 0;
            $tip->processType = '';
            $tip->update();
        }
    }

    // @param obj \App\SubscriptionTipHistory | array of objects
    // will mark processSubscription = 1 and processType = $processType
    // @return void
    public function processSubscriptionTipHistory($event, $processType)
    {
        if (method_exists($event, 'update')) {
            $event->processSubscription = 1;
            $event->processType = $processType;
            $event->update();
            return;
        }

        foreach ($event as $tip) {
            $tip->processSubscription = 1;
            $tip->processType = $processType;
            $tip->update();
        }
    }

    // @param obj $subscription
    // @param string $processType '-1' | '0' | '+1' - for tips
    // @param string $processType '0' | id of new bounus subscription
    // @return void
    public function rollbackSubscription($subscription, $processType)
    {
        if ($subscription->type == 'tips') {
            // put back one tip in tipsBlocked
            if ($processType == '-1')
                $subscription->tipsBlocked++;

            // move one tip from tipLeft to blocked for create original context
            if ($processType == '0') {
                $subscription->tipsLeft--;
                $subscription->tipsBlocked++;
            }
        }

        if ($subscription->type == 'days') {

            // days subsciption only create new bouns subscription
            // delete bonus subscriptions and associated tips history
            if ($processType != '0') {
                $bonusSubscriptionId = $processType;
                // delete bouns subscription
                \App\Subscription::find($bonusSubscriptionId)->delete();
                \App\SubscriptionTipHistory::where('subscriptionId', $bonusSubscriptionId)->delete();
                return;
            }
        }
        $subscription->update();
    }

    public function update() {}

    // delete an existing subscription
    // @param integer $id
    // @return array()
    public function destroy($id)
    {
        $subscription = \App\Subscription::find($id);
        if (! $subscription)
            return [
                'type' => 'error',
                'message' => 'Invalid identifier for subscription.',
            ];

        \App\SubscriptionTipHistory::where('subscriptionId', $subscription->id)->delete();
        \App\SubscriptionRestrictedTip::where('subscriptionId', $subscription->id)->delete();

        // get package
        $package = \App\Package::find($subscription->packageId);

        // delete subscription
        \App\Subscription::where('id', $id)->delete();

        // move package (packages group in no users if is possible)
        $packageInstance = new \App\Http\Controllers\Admin\Package();
        $packageInstance->evaluateAndChangeSection($package->id);


        return [
            'type' => 'success',
            'message' => 'Subscription was deleted with success!',
        ];
    }

}
