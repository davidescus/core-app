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

$app->get('/', function () use ($app) {
    return $app->version() . dfsdsfsdfdsfsdfdsf;
});

$app->get('/test', ['middleware' => 'auth'], function () use ($app) {
    return $app->version();
});

// all routes for administration
$app->group(['prefix' => 'admin'], function ($app) {

    $app->get('/event', function() use ($app) {
        return \App\Event::all();
    });
});
