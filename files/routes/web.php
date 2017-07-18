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

    $app->get('/event', function() use ($app) {
        return \App\Event::all();
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
