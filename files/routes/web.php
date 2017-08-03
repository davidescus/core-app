<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */

use Illuminate\Http\Request;
use Carbon\Carbon;

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->get('/test', ['middleware' => 'auth'], function () use ($app) {
    return $app->version();
});

// all routes for administration
$app->group(['prefix' => 'admin'], function ($app) {

    // dashboard for admin
    $app->get('/', function () use ($app) {
        return view('main');
    });


    /*****************************************************************
     * Manage events
     * **************************************************************/
    $app->get('/event/all', function() use ($app) {
        return \App\Event::all();
    });

    // return distinct providers and leagues based on table selection
    $app->get('/event/info', function(Request $request) use ($app) {

        $table = $request->get('table');

        $data = [
            'tipsters' => [],
            'leagues'  => []
        ];

        if ($table == 'run' || $table == 'ruv') {
            $data['tipsters'] = \App\Event::distinct()->select('provider')
                ->where('eventDate', '>', Carbon::now('UTC')->addMinutes(20))
                ->groupBy('provider')->get();

            $data['leagues'] = \App\Event::distinct()->select('league')
                ->where('eventDate', '>', Carbon::now('UTC')->addMinutes(20))
                ->groupBy('league')->get();
        }

        if ($table == 'nun' || $table == 'nuv') {
            $data['tipsters'] = \App\Event::distinct()->select('provider')
                ->where([
                    ['eventDate', '<', Carbon::now('UTC')->modify('-105 minutes')],
                        ['result', '<>', ''],
                        ['statusId', '<>', '']
                    ])->groupBy('provider')->get();

            $data['leagues'] = \App\Event::distinct()->select('league')
                ->where([
                    ['eventDate', '<', Carbon::now('UTC')->modify('-105 minutes')],
                        ['result', '<>', ''],
                        ['statusId', '<>', '']
                    ])->groupBy('league')->get();
        }

        return $data;
    });

    // return events number or events based on selection: table, proviser, minOdd, maxOdd
    $app->get('/event/{type}', function(Request $request, $type) use ($app) {

        $data = [
            'number' => 0,
            'events' => []
        ];

        $table = $request->get('table');
        $provider = $request->get('provider');
        $league = $request->get('league');
        $minOdd = $request->get('minOdd');
        $maxOdd = $request->get('maxOdd');

        $where = [];
        if ($provider)
            $where[] = ['provider', '=', $provider];

        if ($league)
            $where[] = ['league', '=', $league];

        if ($minOdd)
            $where[] = ['odd', '>=', $minOdd];

        if ($maxOdd)
            $where[] = ['odd', '<=', $maxOdd];

        if ($table == 'run' || $table == 'ruv')
            $where[] = ['eventDate', '>', Carbon::now('UTC')->addMinutes(20)];

        if ($table == 'nun' || $table == 'nuv') {
            $where[] = ['eventDate', '<', Carbon::now('UTC')->modify('-105 minutes')];
            $where[] = ['result', '<>', ''];
            $where[] = ['statusId', '<>', ''];
        }

        if ($type == 'number') {
            $eventNumber = \App\Event::where($where)->count();
            $data['number'] = $eventNumber ? $eventNumber : 0;
        }
        if ($type == 'events') {
            $data['events'] = \App\Event::where($where)->orderBy('eventDate', 'desc')->get();
        }
        return $data;
    });

    /*****************************************************************
     * Manage Associations
     * **************************************************************/

    // get all asociations by table type: run, ruv, nun, nuv
    $app->get('/association/{type}', function($type) use ($app) {
        return \App\Association::where([['type', '=', $type]])->get();
    });

    // create new association
    $app->post("/association", function(Request $request) use ($app) {

        $eventsIds = $request->input('eventsIds');
        $table = $request->input('table');
        $systemDate = $request->input('systemDate');

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
    });

    // delete an association
    $app->delete("/association/{id}", function($id) use ($app) {
        $association = \App\Association::find($id);

        // Site not exists retur status not exists
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
    });

    // get available packages and sites according to associateEvent prediction
    $app->get('/association/package/available/{table}/{associateEventId}', function($table, $associateEventId) use ($app) {

        $data = [];

        $data['event'] = \App\Association::find($associateEventId);

        if (!$data['event'])
            return response()->json([
                "type" => "error",
                "message" => "Event id: $associateEventId not exist anymore!"
            ]);

        // get prediction group
        $prediction = \App\Prediction::select('group')->where('identifier', $data['event']->predictionId)->first();

        // get all packages associated with this prediction group
        $packagesIds = \App\PackagePredictionGroup::select('packageId')->where('predictionGroup', $prediction->group)->get();

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
    });

    /*****************************************************************
     * Manage Tips Distribution
     * **************************************************************/

    // create new Tips Distribution
    // delete unwanted association
    $app->post("/distribution", function(Request $request) use ($app) {

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
        foreach (\App\Distribution::where('associationId', $association['id'])->get() as $item) {
            // delete distribution
            if (!in_array($item->packageId, $packagesIds)) {

                // TODO check if distributed event is available for delete

                $item->delete();
                $deleted++;
            }

            $distributionExists[] = $item->packageId;
        }

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

            // get site prediction name
            $sitePrediction = \App\SitePrediction::where([
                ['siteId', '=', $packageSite->siteId],
                ['predictionIdentifier', '=', $association['predictionId']]
            ])->first();

            // set siteId
            $association['siteId'] = $packageSite->siteId;

            // set tableIdentifier
            $association['tableIdentifier'] = $package->tableIdentifier;

            // set predictionName
            $association['predictionName'] = $sitePrediction->name;

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

    });

    // get all events distributed
    $app->get("/distribution", function(Request $request) use ($app) {

        $data = [];

        $sites = \App\Site::all();
        $dates = \App\Distribution::select('systemDate')->distinct()->get();

        foreach ($dates as $k => $date) {

            $data[$k]['systemDate'] = $date->systemDate;

            foreach ($sites as $site) {

                // set siteName
                $data[$k]['sites'][$site->id]['name'] = $site->name;
                $data[$k]['sites'][$site->id]['id'] = $site->id;

                // get associated packages frm site_package
                $associatedPackages = \App\SitePackage::select('packageId')->where('siteId', $site->id)->get()->toArray();
                foreach ($associatedPackages as $associatedPackage) {

                    // get package
                    $package = \App\Package::find($associatedPackage['packageId']);

                    // get events for package
                    $distributedEvents = \App\Distribution::where('packageId', $package->id)->where('systemdate', $date->systemDate)->get();

                    $data[$k]['sites'][$site->id]['packages'][$associatedPackage['packageId']]['id'] = $package->id;
                    $data[$k]['sites'][$site->id]['packages'][$associatedPackage['packageId']]['name'] = $package->name;
                    $data[$k]['sites'][$site->id]['packages'][$associatedPackage['packageId']]['tipsPerDay'] = $package->tipsPerDay;
                    $data[$k]['sites'][$site->id]['packages'][$associatedPackage['packageId']]['eventsNumber'] = count($distributedEvents);

                    $data[$k]['sites'][$site->id]['packages'][$associatedPackage['packageId']]['events'] = $distributedEvents;
                }
            }
        }

        return [
            'distribution' => $data
        ];
    });

    // get all events distributed
    $app->delete("/distribution", function(Request $request) use ($app) {
        $data = $request->input('data');

        return $data;
    });

    // manual publish events in archive
    $app->post('/archive', function(Request $request) use ($app) {
        $data = $request->input('data');

        $alreadyPublish = 0;
        $inserted = 0;

        if (!$data)
            return [
                "type" => "error",
                "message" => "No events provided!",
            ];

        foreach ($data as $value) {

            $distribution = \App\Distribution::where('id', $value['distributionId'])
                ->where('packageId', $value['packageId'])->first();

            // TODO check if distributed event exists

            if ($distribution->isPublish) {
                $alreadyPublish++;
                continue;
            }

            // set publish
            $distribution->isPublish = 1;
            $distribution->update();

            // transform in array
            $distribution = json_decode(json_encode($distribution), true);

            // remove id and set distributionId
            $distribution['distributionId'] = $distribution['id'];
            unset($distribution['id']);

            // TODO also send event in ArchiveHome

            \App\ArchiveBig::create($distribution);
            $inserted++;
        }

        $message = '';
        if ($alreadyPublish)
            $message .= "$alreadyPublish events already published to archive\r\n";
        if ($inserted)
            $message .= "$inserted events was published to archive\r\n";

        return [
            "type" => "success",
            "message" => $message
        ];
    });

    /*****************************************************************
     * Manage Sites
     * **************************************************************/

    // get all sites
    $app->get('/site', function() use ($app) {
        return \App\Site::all();
    });

    // get specific site by id
    $app->get("/site/{id}", function($id) use ($app) {
        return \App\Site::find($id);
    });

    // update a site
    $app->put("/site/{id}", function(Request $request, $id) use ($app) {
        $site = \App\Site::find($id);

        // Site not exists retur status not exists
        if ($site === null) {
            return response()->json([
                "type" => "error",
                "message" => "Site with id: $id not exists"
            ]);
        }

        // Todo: check if new name is valid
        $site->name = $request->input('name');
        $site->save();
        return response()->json([
            "type" => "success",
            "message" => "Site information was updated with success!"
        ]);
    });

    // store new site
    $app->post("/site", function(Request $request) use ($app) {

        // Todo: check if new name is valid
        $name = $request->input('name');

        // Site name must be unique
        $site = \App\Site::where('name', '=', $name)->first();
        if ($site !== null) {
            return response()->json([
                "type" => "error",
                "message" => "This site already exists!"
            ]);
        }

        $site = \App\Site::create([
            "name" => $name
        ]);
        return response()->json([
            "type" => "success",
            "message" => "New site was added with success!"
        ]);
    });

    // delete a site
    $app->delete("/site/{id}", function($id) use ($app) {
        $site = \App\Site::find($id);

        // Site not exists retur status not exists
        if ($site === null) {
            return response()->json([
                "type" => "error",
                "message" => "Site with id: $id not exists"
            ]);
        }
        $site->delete();
        return response()->json([
            "type" => "success",
            "message" => "Site with id: $id was deleted with success!"
        ]);
    });

    /*****************************************************************
     * Manage Packages
     * **************************************************************/

    // getall packages for a specific site
    $app->get('package-site/{id}', function($id) use ($app) {
        return \App\Package::where('siteId', '=', $id)->get();
    });

    // get specific package by id
    $app->get("/package/{id}", function($id) use ($app) {
        return \App\Package::find($id);
    });

    // update a package
    $app->put("/package/{id}", function(Request $request, $id) use ($app) {
        $pack = \App\Package::find($id);

        // Site not exists retur status not exists
        if ($pack === null) {
            return response()->json([
                "type" => "error",
                "message" => "Package with id: $id not exists anymore"
            ]);
        }

        // Todo: check inputs for validity
        $pack->update($request->all());
        return response()->json([
            "type" => "success",
            "message" => "Package information was updated with success!"
        ]);
    });

    // store new package
    $app->post("/package", function(Request $request) use ($app) {

        // Todo: check inputs for validity

        $pack = \App\Package::create($request->all());
        return response()->json([
            "type" => "success",
            "message" => "New site was added with success!"
        ]);
    });

    // delete a package
    $app->delete("/package/{id}", function($id) use ($app) {
        $pack = \App\Package::find($id);

        // Package not exists retur status not exists
        if ($pack === null) {
            return response()->json([
                "type" => "error",
                "message" => "Package with id: $id not exists"
            ]);
        }
        $pack->delete();
        return response()->json([
            "type" => "success",
            "message" => "Pack with id: $id was deleted with success!"
        ]);
    });
});
