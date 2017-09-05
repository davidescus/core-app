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
use Ixudra\Curl\Facades\Curl;

$app->get('/xml', function () use ($app) {

    $rootDir =  dirname(__DIR__);

    // load xml file
    $xml = file_get_contents('http://tipstersportal.com/feed/matches.php');

    // parse xml content
    $c = Parser::xml($xml);

    // iterate matches
    $count = 0;
    $eventExists = 0;
    foreach ($c['match'] as $k => $match) {

        // check if event already are imported
        if (\App\Match::find($match['id'])) {
            $eventExists++;
            continue;
        }

        // create array
        $m = [
            'id' => $match['id'],
            'country' => $match['tournament_country'],
            'countryCode' => $match['tournament_country_code'],
            'league' => $match['tournament_title'],
            'leagueId' => $match['tournament_id'],
            'homeTeam' => $match['home_team_name'],
            'homeTeamId' => $match['home_team_id'],
            'awayTeam' => $match['away_team_name'],
            'awayTeamId' => $match['away_team_id'],
            'result' => '',
            'eventDate' => $match['utc_date'],
        ];

        // store country name and code if not exists
        if(!\App\Country::where('code', $m['countryCode'])->first()) {

            if (!$m['countryCode']) {
                echo "Missing country code for matchId: " . $m['id'] . "<br/>";
                continue;
            }

            \App\Country::create([
                'code' => $m['countryCode'],
                'name' => $m['country']
            ]);
        }

        if(!file_exists($rootDir . '/public/logo/country/' . $m['countryCode'] . '.png')) {
            $content = @file_get_contents($match['tournament_country_icon']);
            file_put_contents($rootDir . '/public/logo/country/' . $m['countryCode'] . '.png', $content);
        }

        // store league if not exist
        if(!\App\League::find($m['leagueId'])) {
            \App\League::create([
                'id' => $m['leagueId'],
                'name' => $m['league']
            ]);
        }

        // store homeTeam if not exists
        if(!\App\Team::find($m['homeTeamId'])) {
            \App\Team::create([
                'id' => $m['homeTeamId'],
                'name' => $m['homeTeam'],
            ]);
        }

        if(!file_exists($rootDir . '/public/logo/team/' . $m['homeTeamId'] . '.png')) {
            $content = @file_get_contents($match['home_team_logo']);
            file_put_contents($rootDir . '/public/logo/team/' . $m['homeTeamId'] . '.png', $content);
        }

        // store awayTeam if not exists
        if(!\App\Team::find($m['awayTeamId'])) {
            \App\Team::create([
                'id' => $m['awayTeamId'],
                'name' => $m['awayTeam'],
            ]);
        }

        if(!file_exists($rootDir . '/public/logo/team/' . $m['awayTeamId'] . '.png')) {
            $content = @file_get_contents($match['away_team_logo']);
            file_put_contents($rootDir . '/public/logo/team/' . $m['awayTeamId'] . '.png', $content);
        }

        // store new match
        \App\Match::create($m);

        $count++;
    }

    echo " ------------------------------------------------------ <br/> ";
    echo 'Total events: ' . count($c['match']) . "</br>";
    echo 'Already Exists: ' . $eventExists . "</br>";
    echo 'Process Events: ' . $count . "</br>";
});


$app->get('/test', ['middleware' => 'auth', function () use ($app) {
    $user = Auth::user();

    return $user->name;
    return $app->version();
}]);


    /* -------------------------------------------------------------------
     * - CLIENT -
     * all routes group for clients (sites)
     ---------------------------------------------------------------------*/

$app->group(['prefix' => 'client'], function ($app) {

    // @param integer $id
    // get general configuration for site
    // @return array()
    $app->get('/get-configuration/{id}', 'Client\Configuration@index');

    // @param integer $id
    // get archive-big events for site.
    // @return array() indexed by table idintifier.
    $app->get('/update-archive-big/{id}', 'Client\ArchiveBig@index');
});

    /* -------------------------------------------------------------------
     * - ADMIN -
     * all routes group
     ---------------------------------------------------------------------*/

    /*
     * Login to admin section.
     ---------------------------------------------------------------------*/

// each login will generate a new token
// @param string $email
// @param string $password
// @return array()
$app->post('/admin/login', 'Admin\Login@index');

$app->group(['prefix' => 'admin', 'middleware' => 'auth'], function ($app) {

    /*
     * Archive Big
     ---------------------------------------------------------------------*/

    // Archive Big
    // @param integer $id
    // @return event || null
    $app->get('/archive-big/event/{id}', 'Admin\ArchiveBig@get');

    // Archive Big
    // @param integer $siteId
    // @param string $table
    // @param string $date
    // @return array()
    $app->get('/archive-big/month-events', 'Admin\ArchiveBig@getMonthEvents');

    // Archive Big
    // get array with available years and month based on archived events.
    // @return array()
    $app->get('/archive-big/available-months', 'Admin\ArchiveBig@getAvailableMounths');

    // Archive Big
    // @param $id,
    // toogle show/hide an event from archiveBig
    // @return array()
    $app->get('/archive-big/show-hide/{id}', 'Admin\ArchiveBig@toogleShowHide');

    // Archive Big
    // @param $id,
    // @param $siteId,
    // @param $predictionId,
    // @param $StatusId,
    // update prediction and status
    // @return array()
    $app->post('/archive-big/update/prediction-and-status/{id}', 'Admin\ArchiveBig@updatePredictionAndStatus');

    // Archive Big
    // @param $siteId,
    // @param $date format: Y-m,
    // set isPublishInSite 1 for all events fron site in selected month
    // @return array()
    $app->post('/archive-big/publish-month', 'Admin\ArchiveBig@publishMonth');

    /*
     * Sites
     ---------------------------------------------------------------------*/

    // get all sites only ids and names
    // TODO it can be confilict with route site/{is}
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

    // get all alvaillable tables(for archives)
    // @return array()
    $app->get('/site/available-table/{siteId}', 'Admin\Site@getAvailableTables');

    // front request to send update request for client
    // @param integer $id
    // @return array()
    $app->get('/site/update-client/{id}', function ($id) use ($app) {

        $site = \App\Site::find($id);
        if (!$site)
            return [
                'type' => 'error',
                'message' => "Site id: $id not exist enymore.",
            ];

        $response = Curl::to($site->url)
            ->withData([
                'route' => 'api',
                'key' => $site->token,
                'method' => 'updateSiteConfiguration',
                'url' => env('APP_HOST') . '/client/get-configuration/' . $id,
            ])
            ->post();

        $response = json_decode($response, true);
        if (!$response)
            return [
                'type' => 'error',
                'message' => 'Client site not respond, check Website Url and client site availability in browser.',
            ];

        // if success update isConnected
        if ($response['success']) {
            $site->isConnect = 1;
            $site->save();
        }

        return [
            'type' => $response['success'] ? 'success' : 'error',
            'message' => $response['message'],
        ];
    });

    // front request: tell client to update his archive-big
    // @param integer $id
    // @return array()
    $app->get('/site/update-archive-big/{id}', function ($id) use ($app) {

        $site = \App\Site::find($id);
        if (!$site)
            return [
                'type' => 'error',
                'message' => "Site id: $id not exist enymore.",
            ];

        $response = Curl::to($site->url)
            ->withData([
                'route' => 'api',
                'key' => $site->token,
                'method' => 'updateArchiveBig',
                'url' => env('APP_HOST') . '/client/update-archive-big/' . $id,
            ])
            ->post();

        $response = json_decode($response, true);
        if (!$response)
            return [
                'type' => 'error',
                'message' => 'Client site not respond, check Website Url and client site availability in browser.',
            ];

        return [
            'type' => $response['success'] ? 'success' : 'error',
            'message' => $response['message'],
        ];
    });

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
    // @return array()
    $app->get("/prediction", 'Admin\Prediction@index');

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

    // Events
    // @retun object event
    $app->get('/event/by-id/{id}', 'Admin\Event@get');

    // Events
    // @param integer $id
    // @param string  $result
    // @param integer $statusId
    // @retun array()
    $app->post('/event/update-result-status/{id}', 'Admin\Event@updateResultAndStatus');

    // Events
    // get all associated events
    // @return array()
    $app->get('/event/associated-events', 'Admin\Event@getAssociatedEvents');

    // return distinct providers and leagues based on table selection
    $app->get('/event/available-filters-values/{table}', 'Admin\Event@getTablesFiltersValues');

    // return events number or events based on selection: table, provider, league, minOdd, maxOdd
    $app->get('/event/available/number', 'Admin\Event@getNumberOfAvailableEvents');

    // return events based on selection: table, provider, league, minOdd, maxOdd
    $app->get('/event/available', 'Admin\Event@getAvailableEvents');

    // add event from match
    // @param integer $matchId
    // @param string  $predictionId
    // @param string  $odd
    // @return array()
    $app->post('/event/create-from-match', 'Admin\Event@createFromMatch');

    /*
     * Matches
     ---------------------------------------------------------------------*/

    // get all available matches by search
    // @param string $filter
    // @return array()
    $app->get('/match/filter/{filter}', 'Admin\Match@getMatchesByFilter');

    // get match by id
    // @param integer $id
    // @return object
    $app->get('/match/{id}', 'Admin\Match@get');


    /*
     * Associations - 4 tables
     ---------------------------------------------------------------------*/

    // @param string $tableIdentifier : run, ruv, nun, nuv
    // @param string $date format: Y-m-d | 0 | null
    // get all events associated with a table on sellected date
    //     - $data = 0 | null => current date GMT
    // @return object
    $app->get('/association/event/{tableIdentifier}/{date}', 'Admin\Association@index');

    // add no tip to a table
    // @param string $table
    // @param string $systemDate
    // @return array()
    $app->post("/association/no-tip", 'Admin\Association@addNoTip');


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

    // @param int $id
    // delete an association
    //    - Not Delete distributed association
    $app->get("/association/delete/{id}", 'Admin\Association@destroy');

    // get available packages according to table and event prediction
    // @param string  $table
    // @param integer $associateEventId
    // @return array();
    $app->get('/association/package/available/{table}/{associateEventId}', function($table, $associateEventId) use ($app) {
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
    });

    /*
     * Distribution
     ---------------------------------------------------------------------*/

    // Distribution
    // @param string $eventId
    // @param array  $packagesIds
    // delete distributions of event - package (if packageId is not in $packagesIds)
    //    - Not Delete events hwo was already published
    // create new associations event - packages
    $app->post("/distribution", 'Admin\Distribution@storeAndDelete');

    // Distribution
    // @string $date format: Y-m-d || 0 || null
    // get all distributed events for specific date.
    // @return array()
    $app->get("/distribution/{date}", 'Admin\Distribution@index');

    // Distribution
    // @param array $ids
    // delete distributed events
    //   - Not Delete events already sended in archives
    $app->post("/distribution/delete", 'Admin\Distribution@destroy');

    /*
     * Archive
     ---------------------------------------------------------------------*/

    // get all events for all archives
    $app->get('/archive', function() use ($app) {
        return \App\ArchiveBig::all();
    });

    // publish events in archive
    // @param array $ids (distributionId)
    //  - mark events publish in distribution
    //  - send events in archive
    // @return array()
    $app->post('/archive/publish', 'Admin\Archive@publish');

});
