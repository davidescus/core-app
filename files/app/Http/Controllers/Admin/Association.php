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

        return \App\Association::where('type', $tableIdentifier)->where('systemDate', $date)->get();
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

            // TODO check if table is for real users or for no users

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

    public function store() {}

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
