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
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

    /* -------------------------------------------------------------------
     * - TESTING -
     * This to test will not remain here.
     ---------------------------------------------------------------------*/

// test route for sending emails
$app->get('/send-mail-test', function () use ($app) {
    $args = [
        'host'     => 'smtp.goforwinners.com',
        'user'     => 'contact@goforwinners.com',
        'pass'     => '',
        'port'     => 587,
        'from'     => 'contact@goforwinners.com',
        'fromName' => 'test app goforwinners',
        'to'       => 'shob@awsm.ro',
        'toName'   => 'shob',
        'subject'  => 'Test message',
        'body'     => 'This is the body of test message',
    ];
    $sendMail = new \App\Http\Controllers\Admin\Email\SendMail($args);
});

// Cron
// this will add new events in match table.
$app->get('/xml', 'Cron\Portal@newEvents');


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

    // send client (site) request for update his configuration.
    // route for client is hardcore in controller
    //    - /client/client/get-configuration/$clientId
    // @param integer $id
    // @return array()
    $app->get('/site/update-client/{id}', 'Admin\Client\TriggerAction@updateConfiguration');

    // send client (site) request to update his arvhive big
    // route for client is hardcore in controller
    //    - /client/update-archive-big/$clientId
    // @param integer $id
    // @return array()
    $app->get('/site/update-archive-big/{id}', 'Admin\Client\TriggerAction@updateArchiveBig');

    /*
     * Customers
     ---------------------------------------------------------------------*/

    // get all customers from a site filtering email
    // @param integer $siteId
    // @param string  $filter
    // @return array()
    $app->get('customer/search/{siteId}/{filter}', 'Admin\Customer@getCustomersByFilter');

    // create new customer associated with a site
    // @param integer $siteId
    // @param string  $name
    // @param string  $email
    // @param string  $activeEmail
    // @return array()
    $app->post('customer/create/{siteId}', 'Admin\Customer@store');

    /*
     * Packages
     ---------------------------------------------------------------------*/

    // getall packages for a specific site
    // with associated predictions
    $app->get('package-site/{id}', 'Admin\Package@getPackagesBySite');

    // get ids and names for all pacckage associated with site
    // @param integer $siteId
    // @return array()
    $app->get('package-by-site/ids-and-names/{siteId}', 'Admin\Package@getPackagesIdsAndNamesBySite');

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
     * Subscription
     ---------------------------------------------------------------------*/

    // Subscription
    // @param integer $packageId
    // @param string  $name
    // @param integer $subscription
    // @param integer $price
    // @param string  $type days | tips
    // @param string  $dateStart (only for "days" format Y-m-d)
    // @param string  $dateEnd   (only for "days" format Y-m-d)
    // @param string  $customerEmail
    // store new subscription automatic detect if is custom or not
    //  - compare values with original package.
    // @return array()
    $app->post('/subscription/create', 'Admin\Subscription@store');

    // get all subscriptions
    // @return array()
    $app->get('/subscription', 'Admin\Subscription@index');

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


    // create new associations
    // @param array() $eventsIds
    // @param string  $table
    // @param string  $systemDate
    // @return array()
    $app->post("/association", 'Admin\Association@store');

    // @param int $id
    // delete an association
    //    - Not Delete distributed association
    $app->get("/association/delete/{id}", 'Admin\Association@destroy');

    // get available packages according to table and event prediction
    // @param string  $table
    // @param integer $associateEventId
    // @return array();
    $app->get('/association/package/available/{table}/{associateEventId}', 'Admin\Association@getAvailablePackages');

    /*
     * Distribution
     ---------------------------------------------------------------------*/

    // Distribution
    // @param array $ids
    // This will create template for preview-and-send, template will have placeholders.
    // @return array()
    $app->post('/distribution/preview-and-send/preview', 'Admin\Email\Flow@createPreviewWithPlaceholders');

    // Distribution
    // this is use to have a full preview of template with all events included.
    // @param array $ids
    // @return array()
    $app->post('/distribution/preview-and-send/preview-template', 'Admin\Email\Flow@createFullPreview');

    // Distribution
    // @param array $ids
    // @param string|null $template
    $app->post('/distribution/preview-and-send/send', function (Request $r) use ($app) {

        $ids = $r->input('ids');
        $template = $r->input('template');

        // validate events selection
        $validate = new \App\Http\Controllers\Admin\Email\ValidateGroup($ids);
        if ($validate->error)
            return [
                'type' => 'error',
                'message' => $validate->message,
            ];

        $subscriptions = \App\Subscription::where('packageId', $validate->packageId)->get()->toArray();

        if (!$subscriptions)
            return [
                'type'    => 'success',
                'message' => 'No active subscriptions for this package.',
            ];

        // update tips distribution and set mailingDate and is EmailSend
        foreach ($ids as $id) {
            $distribution = \App\Distribution::find($id);
            $distribution->mailingDate = gmdate('Y-m-d H:i:s');
            $distribution->isEmailSend = '1';
            $distribution->update();
        }

        // get email template
        $events = \App\Distribution::whereIn('id', $ids)->get();

        $emails = [];
        $message = "Start sending emails to: \r\n";
        foreach ($subscriptions as $s) {

            $e = $events;

            // Check what tips will send to each user
            // let sey user have 1 tip and package has 3 events - manage users

            $customer = \App\Customer::find($s['customerId']);
            $message .= $customer->name . ' - ' .$customer->email . "\r\n";

            // insert all events in subscription_tip_history
            foreach ($e as $event) {
                // here will use eventId for event table.
                \App\SubscriptionTipHistory::create([
                    'customerId' => $customer->id,
                    'subscriptionId' => $s['id'],
                    'eventId' => $event['eventId'],
                    'siteId'  => $s['siteId'],
                    'isCustom' => $s['isCustom'],
                    'type' => $s['type'],
                    'isNoTip' => $event['isNoTip'],
                    'isVip' => $event['isVip'],
                    'country' => $event['country'],
                    'countryCode' => $event['countryCode'],
                    'league' => $event['league'],
                    'leagueId' => $event['leagueId'],
                    'homeTeam' => $event['homeTeam'],
                    'homeTeamId' => $event['homeTeamId'],
                    'awayTeam' => $event['awayTeam'],
                    'awayTamId' => $event['awayTamId'],
                    'predictionId' => $event['predictionId'],
                    'predictionName' => $event['predictionName'],
                    'eventDate' => $event['eventDate'],
                    'systemDate' => $event['systemDate'],
                ]);
            }

            // replace section in template
            $replaceTips = new \App\Http\Controllers\Admin\Email\ReplaceTipsInTemplate($template, $events, $validate->isNoTip);

            // store all data to send email
            $args = [
                'host'     => '',
                'user'     => '',
                'pass'     => '',
                'port'     => '',
                'from'     => '',
                'fromName' => '',
                'to'       => $customer->activeEmail,
                'toName'   => $customer->name ? $customer->name : $customer->activeEmail,
                'subject'  => '',
                'body'     => $replaceTips->template,
            ];

            $emails[] = $args;

        }


        return [
            'type'    => 'success',
            'message' => $message,
            'emails'  => $emails,
        ];
    });

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
