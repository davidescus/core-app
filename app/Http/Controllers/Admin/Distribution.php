<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Distribution extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /*
     * @string $date format: Y-m-d || 0 || null
     * get all distributed events for specific date.
     * @return array()
     */
    public function index($date = null)
    {
        $data = [];

        // default set current date GMT
        if ($date === null || $date == 0)
            $date = gmdate('Y-m-d');

        foreach (\App\Site::all() as $site) {
            // set siteName
            $data[$site->id]['name'] = $site->name;
            $data[$site->id]['siteId'] = $site->id;

            // get associated packages frm site_package
            $assocPacks = \App\SitePackage::select('packageId')->where('siteId', $site->id)->get()->toArray();
            foreach ($assocPacks as $assocPack) {
                // get package
                $package = \App\Package::find($assocPack['packageId']);

                // get events for package
                $distributedEvents = \App\Distribution::where('packageId', $package->id)->where('systemdate', $date)->get();

                $data[$site->id]['packages'][$assocPack['packageId']]['id'] = $package->id;
                $data[$site->id]['packages'][$assocPack['packageId']]['name'] = $package->name;
                $data[$site->id]['packages'][$assocPack['packageId']]['tipsPerDay'] = $package->tipsPerDay;
                $data[$site->id]['packages'][$assocPack['packageId']]['eventsNumber'] = count($distributedEvents);
                $data[$site->id]['packages'][$assocPack['packageId']]['events'] = $distributedEvents;

                $customerNotEnoughTips = 0;

                // check for customer with not enough tips only for current date
                if ($date == gmdate('Y-m-d')) {
                    $subscriptionInstance = new \App\Http\Controllers\Admin\Subscription();
                    $subscriptionIdsNotEnoughTips = $subscriptionInstance->getSubscriptionsIdsWithNotEnoughTips($package->id);

                    foreach ($subscriptionIdsNotEnoughTips as $subscriptionId) {
                        $subscription = \App\Subscription::find($subscriptionId);

                        // get total availlable tips
                        $totalTips = $subscription->tipsLeft - $subscription->tipsBlocked;
                        $todayTipsNumber = \App\Distribution::where('packageId', $subscription->packageId)
                            ->where('systemDate', gmdate('Y-m-d'))->count();

                        // get number of restricted tips
                        $restrictedTips = \App\SubscriptionRestrictedTip::where('subscriptionId', $subscription->id)
                            ->where('systemDate', gmdate('Y-m-d'))
                            ->count();

                        // increase number of customers who not have enought tips
                        if (($todayTipsNumber - $restrictedTips) > $totalTips)
                            $customerNotEnoughTips++;
                    }
                }

                $data[$site->id]['packages'][$assocPack['packageId']]['customerNotEnoughTips'] = $customerNotEnoughTips;
            }
        }

        return $data;
    }

    public function get() {}

    /*
     * @param string $eventId
     * @param array  $packagesIds
     * delete distributions of event - package (if packageId is not in $packagesIds)
     *    - Not Delete events hwo was already published
     * create new associations event - packages
     */
    public function storeAndDelete(Request $request) {
        // check if association still exist
        if (\App\Association::find($request->input('eventId')) === null)
            return response()->json([
                "type" => "error",
                "message" => "association id: " . $request->input('eventId') . "not exist anymore!"
            ]);

        // get association as object
        $association = \App\Association::where('id', $request->input('eventId'))->first();

        //transform in array
        $association = json_decode(json_encode($association), true);

        unset($association['created_at']);
        unset($association['updated_at']);

        $packagesIds = $request->input('packagesIds') ? $request->input('packagesIds') : [];

        // create array with existing packageId
        // also delete unwanted distribution
        $deleted = 0;
        $distributionExists = [];
        $message = '';
        foreach (\App\Distribution::where('associationId', $association['id'])->get() as $item) {
            // delete distribution
            if (!in_array($item->packageId, $packagesIds)) {

                if ($item->isPublish) {
                    $message .= "Can not delete association with package $item->packageId, was already published\r\n";
                    continue;
                }
                $item->delete();
                $deleted++;
            }
            $distributionExists[] = $item->packageId;
        }

        if ($message !== '')
            return [
                "type" => "error",
                "message" => $message
            ];

        // id from association table became associationId
        $association['associationId'] = $association['id'];
        unset($association['id']);

        $inserted = 0;
        $alreadyExists = 0;
        $message = '';;
        foreach ($packagesIds as $id) {

            // do not insert if already exists
            if (in_array($id, $distributionExists)) {
                $alreadyExists++;
                continue;
            }

            // get package
            $package = \App\Package::find($id);
            if (!$package) {
                $message = "Could not find package with id: $id, maybe was deleted \r\n";
                continue;
            }

            // get siteId by package
            $packageSite = \App\SitePackage::where('packageId', $id)->first();
            if (!$packageSite) {
                $message = "Could not associate event with package id: $id, this package must be associated with a site\r\n";
                continue;
            }

            if (!$association['isNoTip']) {
                // get site prediction name
                $sitePrediction = \App\SitePrediction::where([
                    ['siteId', '=', $packageSite->siteId],
                    ['predictionIdentifier', '=', $association['predictionId']]
                ])->first();

                // set predictionName
                $association['predictionName'] = $sitePrediction->name;
            }

            // set siteId
            $association['siteId'] = $packageSite->siteId;

            // set tableIdentifier
            $association['tableIdentifier'] = $package->tableIdentifier;

            // set tipIdentifier
            $association['tipIdentifier'] = $package->tipIdentifier;

            // set packageId
            $association['packageId'] = $id;

            \App\Distribution::create($association);
            $inserted++;
        }

        if($inserted)
            $message .= "$inserted: new distribution added \r\n";
        if($deleted)
            $message .= "$deleted: distribution was deleted \r\n";
        if($alreadyExists)
            $message .= "$alreadyExists: distribution already exists \r\n";

        return [
            "type" => "success",
            "message" => $message
        ];

    }

    public function update() {}

    /*
     * @param array $ids
     * delete distributed events
     *   - Not Delete events already sended in archives
     */
    public function destroy(Request $r) {
        $ids = $r->input('ids');

        if (!$ids)
            return [
                "type" => "error",
                "message" => "No events provided!",
            ];

        $notFound = 0;
        $canNotDelete = 0;
        $deleted = 0;
        foreach ($ids as $id) {
            $distribution = \App\Distribution::find($id);

            if (!$distribution) {
                $notFound++;
                continue;
            }

            if ($distribution->isPublish) {
                $canNotDelete++;
                continue;
            }

            if ($distribution->isEmailSend) {
                $canNotDelete++;
                continue;
            }

            $distribution->delete();
            $deleted++;
        }

        $message = '';
        if ($notFound)
            $message .= "$notFound events not founded, maybe was deleted.\r\n";
        if ($canNotDelete)
            $message .= "$canNotDelete can not be deleted.\r\n";
        if ($deleted)
            $message .= "$deleted events was successful deleted.\r\n";

        return [
            "type" => "success",
            "message" => $message
        ];
    }
}
