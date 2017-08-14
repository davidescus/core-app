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

$app->get('/test', ['middleware' => 'auth'], function () use ($app) {
    return $app->version();
});

// all routes for administration
$app->group(['prefix' => 'admin'], function ($app) {

    /*
     * Admin Dashboard
     ---------------------------------------------------------------------*/
    $app->get('/', function () use ($app) {
        return view('main');
    });

    /*
     * Sites
     ---------------------------------------------------------------------*/

    // get all sites only ids and names
    $app->get('/site/ids-and-names', 'Admin\Site@getIdsAndNames');

    // get all sites with all proprieties
    $app->get('/site', 'Admin\Site@index');

    // get specific site by id
    $app->get("/site/{id}", 'Admin\Site@get');

    // store new site
    $app->post("/site", 'Admin\Site@store');

    // update a site
    $app->post("/site/update/{id}", 'Admin\Site@update');

    // delete a site
    $app->get("/site/delete/{id}", 'Admin\Site@destroy');

    /*
     * Packages
     ---------------------------------------------------------------------*/

    // getall packages for a specific site
    $app->get('package-site/{id}', 'Admin\Package@getPackagesBySite');

    // get specific package by id
    $app->get("/package/{id}", 'Admin\Package@get');

    // update a package
    $app->post("/package/update/{id}", 'Admin\Package@update');

    // store new package
    $app->post("/package", 'Admin\Package@store');

    // delete a package
    $app->get("/package/delete/{id}", 'Admin\Package@destroy');

    /*
     * Predictions
     ---------------------------------------------------------------------*/

    // get all predictions order by group
    $app->get("/prediction", function() use ($app) {

        $pred = \App\Prediction::all();

        $data = [];
        foreach ($pred as $p) {
            $data[$p['group']]['predictions'][] = $p;
        }

        return $data;
    });

    /*
     * Site Prediction
     ---------------------------------------------------------------------*/

    // get all predictions names for a site
    $app->get('/site-prediction/{siteId}', 'Admin\SitePrediction@index');

    // update or create all predictions names for a site
    $app->post('/site-prediction/update/{siteId}', 'Admin\SitePrediction@storeOrUpdate');

    /*
     * Site Package
     ---------------------------------------------------------------------*/

    // get all packages ids associated with site
    $app->get('/site-package/{siteId}', 'Admin\SitePackage@get');

    // store if not exists a new association site - package
    $app->post('/site-package', 'Admin\SitePackage@storeIfNotExists');

    /*
     * Site Result Status
     ---------------------------------------------------------------------*/

    // get all results name and statuses for a site
    $app->get('/site-result-status/{siteId}', 'Admin\SiteResultStatus@index');

    // update or create all results name and statuses for a site
    $app->post('/site-result-status/update/{siteId}', 'Admin\SiteResultStatus@storeOrUpdate');

    /*
     * Package Prediction
     ---------------------------------------------------------------------*/

    // delete all package predictions and create allnew assocaitions
    $app->post('/package-prediction', 'Admin\PackagePrediction@deleteAndStore');

    /*
     * Events
     ---------------------------------------------------------------------*/

    $app->get('/event/all', 'Admin\Event@index');

    // return distinct providers and leagues based on table selection
    $app->get('/event/available-filters-values/{table}', 'Admin\Event@getTablesFiltersValues');

    // return events number or events based on selection: table, provider, league, minOdd, maxOdd
    $app->get('/event/available/number', 'Admin\Event@getNumberOfAvailableEvents');

    // return events based on selection: table, provider, league, minOdd, maxOdd
    $app->get('/event/available', 'Admin\Event@getAvailableEvents');

    /*
     * Associations - 4 tables
     ---------------------------------------------------------------------*/

    // get all asociations by tableIdentifier : run, ruv, nun, nuv
    $app->get('/association/{tableIdentifier}', 'Admin\Association@index');

    // create new association
    // TODO this not work in controller do not know why
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
    $app->get("/association/delete/{id}", 'Admin\Association@destroy');

    // get available packages and sites according to associateEvent prediction
    $app->get('/association/package/available/{table}/{associateEventId}', function($table, $associateEventId) use ($app) {

        $data = [];

        $data['event'] = \App\Association::find($associateEventId);

        if (!$data['event'])
            return response()->json([
                "type" => "error",
                "message" => "Event id: $associateEventId not exist anymore!"
            ]);

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

    /*
     * Distributions
     ---------------------------------------------------------------------*/

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
        $ids = $request->input('ids');

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
    });

    // get all events for all archives
    $app->get('/archive', function() use ($app) {
        return [
            "events" => \App\ArchiveBig::all()
        ];
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



});
