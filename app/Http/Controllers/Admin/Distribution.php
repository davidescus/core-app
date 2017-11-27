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

                // add status to distributed events
                foreach ($distributedEvents as $distributedEvent)
                    $distributedEvent->status;

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

    // @param $timeStart format h:mm || hh:mm
    // @param $timeEndformat h:mm || hh:mm
    // will create date schedule, when email will be send.
    // @return array()
    public function createEmailSchedule(Request $r)
    {
        $timeStart = $r->input('timeStart');
        $timeEnd = $r->input('timeEnd');

        if (!$timeStart || ! $timeEnd)
            return [
                'type' => 'error',
                'message' => 'Please choose time to start and end.',
            ];

        $hStart = explode(':', $timeStart)[0];
        $hStart = strlen($hStart) == 1 ? '0' . $hStart : $hStart;
        $mStart = explode(':', $timeStart)[1];

        $hEnd = explode(':', $timeEnd)[0];
        $hEnd = strlen($hEnd) == 1 ? '0' . $hEnd : $hEnd;
        $mEnd = explode(':', $timeEnd)[1];

        $timeStart = strtotime(gmdate('Y-m-d') . ' ' . $hStart . ':' . $mStart . ':00');
        $timeEnd = strtotime(gmdate('Y-m-d') . ' ' . $hEnd . ':' . $mEnd . ':00');

        if ($timeStart  < (time() + (10 * 60)))
            return [
                'type' => 'error',
                'message' => "Start must be greather with 10 min than current GMT time: \n" . gmdate('Y-m-d H:i:s'),
            ];

        if ($timeStart + (5 * 60) > $timeEnd)
            return [
                'type' => 'error',
                'message' => "End must be greather than start with minimum 5 minutes",
            ];

        $events = \App\Distribution::where('isEmailSend', '0')
            ->where('systemDate', gmdate('Y-m-d H:i:s'))
            ->where('eventDate', '>', gmdate('Y-m-d H:i:s', strtotime('+10min')))
            ->whereNull('mailingDate')
            ->get();

        // TODO get sites with common users and implement distance beetwentime for those sites.

        $emailScheduler = new \App\Src\Distribution\EmailSchedule($events, [], $timeStart, $timeEnd);
        $emailScheduler->createSchedule();
        $events = $emailScheduler->getEvents();

        foreach ($events as $event)
            $event->save();

        return [
            'type' => 'success',
            'message' => 'Email Scheduler was created with success for: ' . count($events) .' events!',
        ];
    }

    // will delete date scheduled for events that not sended by email yet.
    // This wil worl only for today events
    // @return array()
    public function deleteEmailSchedule()
    {
        $events = \App\Distribution::where('isEmailSend', '0')
            ->where('systemDate', gmdate('Y-m-d'))
            ->whereNotNull('mailingDate')
            ->where('mailingDate', '>', date('Y-m-d H:i:s', time() + 60))
            ->get();

        foreach ($events as $e) {
            $e->mailingDate = null;
            $e->save();
        }

        return [
            'type' => 'success',
            'message' => 'Was canceled schedule for: ' . count($events) .' events!',
        ];
    }

    public function setTimeEmailSchedule(Request $r)
    {
        $ids = $r->input('ids');
        $time = $r->input('time');

        if (!$ids)
            return [
                'type' => 'error',
                'message' => 'No events selected!',
            ];

        $hTime = explode(':', $time)[0];
        $hTime = strlen($hTime) == 1 ? '0' . $hTime : $hTime;
        $mTime = explode(':', $time)[1];
        $mailingDate = gmdate('Y-m-d') . ' ' . $hTime . ':' . $mTime . ':00';

        if ($mailingDate < gmdate('Y-m-d H:i:s', strtotime('+2min')))
            return [
                'type' => 'error',
                'message' => 'Datethat you selected must be greather with 2 minutes then current GMT date!',
            ];

        $alreadySend = 0;
        $notAvailable = 0;
        $greatherThanEventDate = 0;
        $modified = 0;
        $events = \App\Distribution::whereIn('id', $ids)->get();
        foreach ($events as $e) {
            if ($e->isEmailSend) {
                $alreadySend++;
                continue;
            }
            if ($e->eventDate < gmdate('Y-m-d H:i:s', strtotime('+2min'))) {
                $notAvailable++;
                continue;
            }
            if ($e->eventDate < $mailingDate) {
                $greatherThanEventDate++;
                    continue;
            }

            $e->mailingDate = $mailingDate;
            $e->save();
            $modified++;
        }

        $message = '';
        if($alreadySend)
            $message .= "$alreadySend: already send by email. \r\n";
        if($notAvailable)
            $message .= "$notAvailable: start in less then 2 minutes.\r\n";
        if($greatherThanEventDate)
            $message .= "$greatherThanEventDate: new mailing date is greather than event date.\r\n";
        if($modified)
            $message .= "$modified: was modified.\r\n";

        return [
            'type' => 'success',
            'message' => $message,
        ];
    }

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
        $distributedEvents = \App\Distribution::where('associationId', $association['id'])->get();

        // group packages by site and tipIdentifier
        $group = [];
        foreach ($distributedEvents as $item) {
            if ($item->isPublish || $item->isEmailSend)
                $group[$item->siteId][$item->tipIdentifier] = true;
        }

        foreach ($distributedEvents as $item) {
            // delete distribution
            if (!in_array($item->packageId, $packagesIds)) {

                if (isset($group[$item->siteId][$item->tipIdentifier])) {
                    $message .= "Can not delete association with package $item->packageId, was already published or email send. Or nother package with same tip publish this event.\r\n";
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
