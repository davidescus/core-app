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
            $data['events'] = \App\Event::where($where)->get();
        }
        return $data;
    });

    /*****************************************************************
     * Manage Associations
     * **************************************************************/

    // get all asociations by table type: run, ruv, nun, nuv
    $app->get('/association/{type}', function($type) use ($app) {
        return \App\Site::where(['type', '=', $type]);
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


        return $eventsIds;

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
