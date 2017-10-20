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

        // get all package group with same tip
        $packageGroup = \App\Package::select('id')->where('tipIdentifier', $package->tipIdentifier)
            ->where('siteId', $siteId)
            ->get();

        $packagesIds = [];
        foreach ($packageGroup as $p)
            $packagesIds[] = $p->id;

        $todayDistributedEvents = \App\Distribution::whereIn('packageId', $packagesIds)
            ->where('systemDate', gmdate('Y-m-d'))
            ->get();

        //$activation = new \App\Src\Subscription\ActivationNowCheck($todayDistributedEvents);
        //$activation->checkPublishEventsInNoUsers();
        //if ($activation->isValid) {
        //    $status = 'active';
        //}

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
    // integer $statusId
    public function processSubscriptions($eventId, $statusId)
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

                    // if subscription is valid or was archived last day
                    // we still can add one day to current subscription
                    if (strtotime($subscription->dateEnd) >= (strtotime(gmdate('Y-m-d')) - 60 *60 *24)) {

                        $this->rollbackSubscription($subscription, $tip->processType);

                        // set unProcess all subscriptionTipsHistory for event systemDate
                        foreach ($allEventsInDay as $event) {
                            $event->processSubscription = '0';
                            $event->processType = '';
                            $event->update();
                        }
                        $this->manageDaysSubscriptionStatus($subscription, $tip->systemDate);
                    }

                    // if is changed an event from a subscription hwo was archivided
                    // before more than 1 day will not give a day to current subscription,
                    // will create a bonus subscription
                    else {

                        $this->rollbackSubscription($subscription, $tip->processType);

                        if (!$haveWinLossDraw) {
                            $bonusSubscription = json_decode(json_encode($subscription), true);
                            unset($bonusSubscription['id']);
                            unset($bonusSubscription['created_at']);
                            unset($bonusSubscription['updated_at']);
                            $bonusSubscription['parentId'] = $subscription->id;
                            $bonusSubscription['dateStart'] = gmdate('Y-m-d');
                            $bonusSubscription['dateEnd'] = gmdate('Y-m-d');
                            $bonusSubscription['status'] = 'active';

                            $bonus = \App\Subscription::create($bonusSubscription);

                            foreach ($allEventsInDay as $event) {
                                $event->processSubscription = '1';
                                $event->processType = '+1bonus:' . $bonus->id;
                                $event->update();
                            }
                            continue;
                        }

                        // mark process with 0
                        foreach ($allEventsInDay as $event) {
                            $event->processSubscription = '1';
                            $event->processType = '0';
                            $event->update();
                        }
                        continue;
                    }
                }

                // process on win loss draw
                if ($haveWinLossDraw) {
                    foreach ($allEventsInDay as $event) {
                        $event->processSubscription = '1';
                        $event->processType = '0';
                        $event->update();
                    }

                    $this->manageDaysSubscriptionStatus($subscription, $tip->systemDate);
                    continue;
                }

                // there is events and all finished
                // there is no win, loss, draw
                // there is no noTip
                // only postp - add one day
                if ($allEventsFinished) {
                    foreach ($allEventsInDay as $event) {
                        $event->processSubscription = '1';
                        $event->processType = '+1';
                        $event->update();
                    }

                    // add one day to subscription
                    $subscription->dateEnd = date('Y-m-d', strtotime('+1day', strtotime($subscription->dateEnd)));
                    $subscription->update();

                    $this->manageDaysSubscriptionStatus($subscription, $tip->systemDate);
                    continue;
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
    // @param string $processType '-1' | '0' '+1'
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

            // delete bonus subscriptions and associated tips history
            if (strpos($processType, 'bonus') !== false) {
                $id = trim(explode(':', $processType)[1]);
                \App\Subscription::find($id)->delete();
                \App\SubscriptionTipHistory::where('subscriptionId', $id)->delete();
                return;
            }

            if (!$subscription->isVip)
                if ($processType == '+1')
                    $subscription->dateEnd = date('Y-m-d', strtotime('-1day', strtotime($subscription->dateEnd)));
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
