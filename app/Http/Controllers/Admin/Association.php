<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Association extends Controller
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

    /* @param string $tableIdentifier run|ruv|nun|nuv
     * @param string $date format: Y-m-d | 0 | null
     *     - $date = 0 | null => current date GMT
     * @return array()
     */
    public function index($tableIdentifier, $date)
    {
        if ($date === null || $date == 0)
            $date = gmdate('Y-m-d');

        $associations = \App\Association::where('type', $tableIdentifier)->where('systemDate', $date)->get();
        foreach ($associations as $association)
            $association->status;

        return $associations;
    }

    public function get() {}

    // get available packages according to table and event prediction
    // @param string  $table
    // @param integer $associateEventId
    // @return array();
    public function getAvailablePackages($table, $associateEventId)
    {
        $data = [];
        $data['event'] = \App\Association::find($associateEventId);

        if (!$data['event'])
            return response()->json([
                "type" => "error",
                "message" => "Event id: $associateEventId not exist anymore!"
            ]);

        // if event is noTip will get packages according to table, and users
        // and without any tip associated yet.
        if ($data['event']->isNoTip) {

            $where = [];
            if ($table == "ruv" || $table == "nuv")
                $where[] = ['isVip', '=', '1'];
            elseif ($table == "run" || $table == "nun")
                $where[] = ['isVip', '!=', '1'];

            $keys = [];
            $increments = 0;
            $packages = \App\Package::where($where)->get()->toArray();
            foreach ($packages as $p) {

                // if package has already associate tip continue;
                if (\App\Distribution::where('packageId', $p['id'])
                    ->where('systemDate', $data['event']->systemDate)
                    ->where('isNoTip', '0')->count())
                {
                    continue;
                }

                // get site
                $sitePackage = \App\SitePackage::where('packageId', $p['id'])->first();
                $site = \App\Site::find($sitePackage->siteId);

                // create array
                if (!array_key_exists($site->name, $keys)) {
                    $keys[$site->name] = $increments;
                    $increments++;
                }

                // check if event alredy exists in tips distribution
                $distributionExists = \App\Distribution::where([
                    ['associationId', '=', $data['event']->id],
                    ['packageId', '=', $p['id']]
                ])->count();

                // get event systemDate
                $eventSystemDate = date('Y-m-d', strtotime($data['event']->systemDate));
                // get number of associated events with package on event systemDate
                $eventsExistsOnSystemDate = \App\Distribution::where([
                    ['packageId', '=', $p['id']],
                    ['systemDate', '=', $eventSystemDate]
                ])->count();

                $data['sites'][$keys[$site->name]]['siteName'] = $site->name;
                $data['sites'][$keys[$site->name]]['packages'][] = [
                    'id' => $p['id'],
                    'name' => $p['name'],
                    'tipsPerDay' => $p['tipsPerDay'],
                    'eventIsAssociated' => $distributionExists,
                    'packageAssociatedEventsNumber' => $eventsExistsOnSystemDate,
                ];
            }

            return $data;
        }

        // get all packages associated with this prediction
        $packagesIds = \App\PackagePrediction::select('packageId')->where('predictionIdentifier', $data['event']->predictionId)->get();

        $keys = [];
        $increments = 0;
        foreach ($packagesIds as $p) {
            // get package
            $package = \App\Package::find($p->packageId);

            // check if table is vip or not
            if ($table == "ruv" || $table == "nuv") {
                if (!$package->isVip)
                    continue;
            } elseif ($table == "run" || $table == "nun") {
                if ($package->isVip)
                    continue;
            }

            // check if package has active subscriptions
            $hasSubscriptions = \App\Subscription::where('packageId', $package->id)
                ->where('status', 'active')->count();

            if ($table == "run" || $table == "ruv") {
                if (! $hasSubscriptions)
                    continue;
            } elseif ($table == "nun" || $table == "nuv") {
                if ($hasSubscriptions)
                    continue;
            }

            // get site
            $site = \App\Site::find($package->siteId);

            // create array
            if (!array_key_exists($site->name, $keys)) {
                $keys[$site->name] = $increments;
                $increments++;
            }

            // check if event alredy exists in tips distribution
            $distributionExists = \App\Distribution::where([
                ['associationId', '=', $data['event']->id],
                ['packageId', '=', $package->id]
            ])->count();

            // get event systemDate
            $eventSystemDate = date('Y-m-d', strtotime($data['event']->systemDate));

            // if package already have noTip continue;
            if (\App\Distribution::where([
                ['packageId', '=', $package->id],
                ['systemDate', '=', $eventSystemDate],
                ['isNoTip', '=', '1']])->count())
            {
                continue;
            }

            // get number of associated events with package on event systemDate
            $eventsExistsOnSystemDate = \App\Distribution::where([
                ['packageId', '=', $package->id],
                ['systemDate', '=', $eventSystemDate]
            ])->count();

            $data['sites'][$keys[$site->name]]['siteName'] = $site->name;
            $data['sites'][$keys[$site->name]]['packages'][] = [
                'id' => $package->id,
                'name' => $package->name,
                'tipsPerDay' => $package->tipsPerDay,
                'eventIsAssociated' => $distributionExists,
                'packageAssociatedEventsNumber' => $eventsExistsOnSystemDate,
            ];
        }

        return $data;
    }

    // create new associations
    // @param array() $eventsIds
    // @param string  $table
    // @param string  $systemDate
    // @return array()
    public function store(Request $r)
    {
        $eventsIds = $r->input('eventsIds');
        $table = $r->input('table');
        $systemDate = $r->input('systemDate');

        if (empty($eventsIds))
            return response()->json([
                "type" => "error",
                "message" => "You must select at least one event"
            ]);

        // TODO check $systemDate is a vlid date

        $vip = ($table === 'ruv' || $table === 'nuv') ? '1' : '';

        $notFound = 0;
        $alreadyExists = 0;
        $success = 0;
        $returnMessage = '';

        foreach ($eventsIds as $id) {

            if (!\App\Event::find($id)) {
                $notFound++;
                continue;
            }

            $event = \App\Event::find($id)->toArray();

            // Check if already exists in association table
            if (\App\Association::where([
                ['eventId', '=', (int)$id],
                ['type', '=', $table],
                ['predictionId', '=', $event['predictionId']],
            ])->count()) {
                $alreadyExists++;
                continue;
            }

            $event['eventId'] = (int)$event['id'];
            unset($event['id']);
            unset($event['created_at']);
            unset($event['updated_at']);

            $event['isNoTip'] = '';
            $event['isVip'] = $vip;
            $event['type'] = $table;
            $event['systemDate'] = $systemDate;

            \App\Association::create($event);
            $success++;
        }

        if ($notFound)
            $returnMessage .= $notFound . " - events not found (maybe was deleted)\r\n";

        if ($alreadyExists)
            $returnMessage .= $alreadyExists . " - already associated with this table\r\n";

        if ($success)
            $returnMessage .= $success . " - events was added with success\r\n";

        return response()->json([
            "type" => "success",
            "message" => $returnMessage
        ]);
    }

    // add no tip to a table
    // @param string $table
    // @param string $systemDate
    // @return array()
    public function addNoTip(Request $r)
    {
        $table = $r->input('table');
        $systemDate = $r->input('systemDate');

        // check if already exists no tip in selected date
        if (\App\Association::where('type', $table)
            ->where('isNoTip', '1')
            ->where('systemDate', $systemDate)->count())
        {
            return response()->json([
                "type" => "error",
                "message" => "Already exists no tip table in selected date",
            ]);
        }

        $a = new \App\Association();
        $a->type = $table;
        $a->isNoTip = '1';

        if ($table === 'ruv' || $table === 'nuv')
            $a->isVip = '1';

        $a->systemDate = $systemDate;
        $a->save();

        return response()->json([
            "type" => "success",
            "message" => "No Tip was added with success!",
        ]);
    }

    public function update() {}

    public function destroy($id) {

        $association = \App\Association::find($id);

        // assoociation not exists retur status not exists
        if ($association === null) {
            return response()->json([
                "type" => "error",
                "message" => "Event with id: $id not exists"
            ]);
        }

        // could not delete an already distributed association
        if (\App\Distribution::where('associationId', $id)->count())
        return response()->json([
            "type" => "error",
            "message" => "Before delete event: $id  you must delete all distribution of this!"
        ]);

        $association->delete();
        return response()->json([
            "type" => "success",
            "message" => "Site with id: $id was deleted with success!"
        ]);
    }
}
