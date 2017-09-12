<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Subscription extends Controller
{

    public function index() {}

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
            'customerId' => $customer->id,
            'siteId' => $siteId,
            'packageId' => $package->id,
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

    public function update() {}

    public function destroy() {}

}
