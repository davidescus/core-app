<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Subscription extends Controller
{

    // get all subscriptions
    // @return array()
    public function index() {
        return \App\Subscription::all()->toArray();
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
            'status' => 'active',
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
    // integer $statusId
    public function processSubscriptions($eventId, $statusId)
    {

        // get all subscriptions tips history associated with event
        $tipsHistory = \App\SubscriptionTipHistory::where('eventId', $eventId)->get();
        foreach ($tipsHistory as $tip) {

            if ($tip->type === 'tips') {

                $subscription = \App\Subscription::find($tip->subscriptionId);

                // if has already process subscriptions rollback
                // this will create the original context before the tip process subscription
                if ($tip->processSubscription) {
                    $this->rollbackTipsSubscription($subscription, $tip->processType);
                    $this->unProcessSubscriptionTipHistory($tip);
                    $this->manageTipsSubscriptionStatus($subscription);
                }

                // win
                if ($statusId == 1) {
                    $this->processSubscriptionTipHistory($tip, '-1');

                    if ($subscription->tipsBlocked > 0) {
                        $subscription->tipsBlocked--;
                        $subscription->update();
                    }

                    $this->manageTipsSubscriptionStatus($subscription);
                }

                // loss | draw
                if ($statusId == 2 || $statusId == 3) {

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
                if ($statusId == 4) {
                    $this->processSubscriptionTipHistory($tip, '0');

                    $subscription->tipsLeft++;
                    $subscription->tipsBlocked--;
                    $subscription->update();

                    $this->manageTipsSubscriptionStatus($subscription);
                }
            }
        }
    }

    // @param obj $subscription \App\Http\controllers\Admin\Subscription
    // will evaluate and modyfi the subscription status
    // @return void
    public function manageTipsSubscriptionStatus($subscription)
    {
        $status = 'active';
        if (($subscription->tipsBlocked + $subscription->tipsLeft) < 1)
            $status = 'archived';

        if ($status != $subscription->status) {
            $subscription->status = $status;
            $subscription->update();
        }
    }

    // @param obj \App\SubscriptionTipHistory
    // will mark processSubscription = 0 and processType = ''
    // @return void
    public function unProcessSubscriptionTipHistory($tip)
    {
        $tip->processSubscription = 0;
        $tip->processType = '';
        $tip->update();
    }

    // @param obj \App\SubscriptionTipHistory
    // will mark processSubscription = 1 and processType = $processType
    // @return void
    public function processSubscriptionTipHistory($tip, $processType)
    {
        $tip->processSubscription = 1;
        $tip->processType = $processType;
        $tip->update();
    }

    // @param obj $subscription
    // @param string $processType '-1' | '0'
    // @return void
    public function rollbackTipsSubscription($subscription, $processType)
    {
        // put back one tip in tipsBlocked
        if ($processType == '-1') {
            $subscription->tipsBlocked++;
        }

        // move one tip from tipLeft to blocked for create original context
        if ($processType == '0') {
            $subscription->tipsLeft--;
            $subscription->tipsBlocked++;
        }

        $subscription->update();
    }

    public function update() {}

    public function destroy() {}

}
