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

use Illuminate\Support\Facades\Artisan;


    /* -------------------------------------------------------------------
     * - TESTING -
     * This to test will not remain here.
     ---------------------------------------------------------------------*/

$app->get('/some-route', ['middleware' => 'auth', function () use ($app) {
    $user = Auth::user();

    return $user->name;
    return $app->version();
}]);

    /* -------------------------------------------------------------------
     * - RESET -
     * Here we can reset application.
     ---------------------------------------------------------------------*/

// reset entire aplication
$app->get('/reset', function () use ($app) {
    Artisan::call('migrate:refresh');
    Artisan::call('db:seed');
    return "Application was reset!";
});

// import events
$app->get('/import-events', function () use ($app) {
    new \App\Http\Controllers\Cron\PortalNewEvents();
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
     * Archive Home
     ---------------------------------------------------------------------*/

    // Info
    // @return array()
    $app->get('/info/dates', function () use ($app) {
        return [
            '+3day' => gmdate('Y-m-d', strtotime('+3day')),
            '+2day' => gmdate('Y-m-d', strtotime('+2day')),
            '+1day' => gmdate('Y-m-d', strtotime('+1day')),
            'today' => gmdate('Y-m-d'),
            'time' => gmdate('H:i'),
            '-1day' => gmdate('Y-m-d', strtotime('-1day')),
            '-2day' => gmdate('Y-m-d', strtotime('-2day')),
            '-3day' => gmdate('Y-m-d', strtotime('-3day')),
        ];
    });

    /*
     * Archive Home
     ---------------------------------------------------------------------*/

    // Archive Home
    // @param integer $id
    // @return event || null
    $app->get('/archive-home/event/{id}', 'Admin\ArchiveHome@get');

    // Archive Home
    // @param $id - event id,
    // @param $siteId,
    // @param $country,
    // @param $league,
    // @param $stringEventDate,
    // @param $homeTeam,
    // @param $awayTeam,
    // @param $predictionId,
    // @param $statusId,
    // update event in archive home
    // @return array()
    $app->post('/archive-home/update/{id}', 'Admin\ArchiveHome@update');

    // Archive Home
    // @param integer $siteId
    // @param string $table
    // @return array()
    $app->get('/archive-home/table-events', 'Admin\ArchiveHome@index');

    // Archive Home
    // @param array $order
    // This will save modified order for events in archive big
    // @return void
    $app->post('/archive-home/set-order', 'Admin\ArchiveHome@setOrder');

    // Archive Home Configuration
    // @param integer $siteId
    // @param string $tableIdentifier
    // @param integer $eventsNumber
    // @param integer $dateStart
    // This will save configuration (archive home) for each table in each site
    // After save will delete exceded events number
    // @return array
    $app->post('/archive-home/save-configuration', 'Admin\ArchiveHome@saveConfiguration');

    // Archive Home
    // @param $id,
    // toogle show/hide an event from archivHome
    // @return array()
    $app->get('/archive-home/show-hide/{id}', 'Admin\ArchiveHome@toogleShowHide');

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

    // send client (site) request to update his arvhive home
    // route for client is hardcore in controller
    //    - /client/update-archive-home/$clientId
    // @param integer $id
    // @return array()
    $app->get('/site/update-archive-home/{id}', 'Admin\Client\TriggerAction@updateArchiveHome');

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

    // get statusId for event by result
    // @param string eventId
    // @param string result
    // @return array()
    $app->post("/prediction/status-by-result/{eventId}", function(Request $r, $eventId) use ($app) {
        $result = $r->input('result');

        $event = \App\Event::find($eventId);

        $statusByScore = new \App\Src\Prediction\SetStatusByScore($result, $event->predictionId);
        $statusByScore->evaluateStatus();
        $statusId = $statusByScore->getStatus();

        return [
            'type' => $statusId > 0 ? 'success' : 'error',
            'statusId' => $statusId,
        ];
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

    // Subscription
    // @param integer $id
    // delete a subscription
    // @return array()
    $app->get('/subscription/delete/{id}', 'Admin\Subscription@destroy');

    // Subscription
    // @param int $id
    // get specific subscription by id
    // @return array()
    $app->get('/subscription/{id}', 'Admin\Subscription@get');

    // Subscription
    // get all subscriptions
    // @return array()
    $app->get('/subscription', 'Admin\Subscription@index');

    // Subscription
    // @param int $id
    // @param string $value
    // update subscrription tipsLeft for tips, dateEnd for days
    // @return array()
    $app->post('/subscription/edit/{id}', 'Admin\Subscription@update');

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
    $app->post('/event/update-result-status/{id}', function(Request $r, $id) use ($app) {
        $result = $r->input('result');
        $statusId = $r->input('statusId');

        $eventInstance = new \App\Http\Controllers\Admin\Event();
        return $eventInstance->updateResultAndStatus($id, $result, $statusId);
    });

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
    $app->get('/match/filter/{table}/{filter}', 'Admin\Match@getMatchesByFilter');

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
    $app->get('/association/package/available/{table}/{associateEventId}/{date}', 'Admin\Association@getAvailablePackages');

    /*
     * Distribution
     ---------------------------------------------------------------------*/

    // Distribution
    // @param array $ids
    // This will create template for preview-and-send, template will have placeholders.
    // @return array()
    $app->post('/distribution/preview-and-send/preview', 'Admin\Email\Flow@createPreviewWithPlaceholders');

    // Distribution
    // @param $timeStart format h:mm || hh:mm
    // @param $timeEndformat h:mm || hh:mm
    // will create date schedule, when email will be send.
    // @return array()
    $app->post('/distribution/create-email-schedule', 'Admin\Distribution@createEmailSchedule');

    // Distribution
    // will delete date scheduled for events that not sended by email yet.
    // This wil worl only for today events
    // @return array()
    $app->get('/distribution/delete-email-schedule', 'Admin\Distribution@deleteEmailSchedule');

    // Distribution
    // Will set date when selects events will be sended by email.
    // @return array()
    $app->post('/distribution/set-time-email-schedule', 'Admin\Distribution@setTimeEmailSchedule');

    // Distribution
    // this is use to have a full preview of template with all events included.
    // @param array $ids
    // @return array()
    $app->post('/distribution/preview-and-send/preview-template', 'Admin\Email\Flow@createFullPreview');

    // Distribution
    // @param array $ids
    // @param string|null|false $template
    // This will add events to subscriptions, also will move events to email schedule.
    $app->post('/distribution/preview-and-send/send', function (Request $r) use ($app) {

        $ids = $r->input('ids');
        $template = $r->input('template');

        if (! $template) {
            $group = [];
            $events = \App\Distribution::whereIn('id', $ids)->get();
            foreach ($events as $e) {
                $group[$e->packageId][] = $e->id;
            }

            $message = '';
            foreach ($group as $gids) {
                $distributionInstance = new \App\Http\Controllers\Admin\Distribution();
                $result = $distributionInstance->associateEventsWithSubscription($gids);
                $message .= $result['message'];
            }

            return [
                'type' => 'success',
                'message' => $message,
            ];
        }

        $distributionInstance = new \App\Http\Controllers\Admin\Distribution();
        return $distributionInstance->associateEventsWithSubscription($ids, $template);
    });

    // Distribution
    // manage customer restricted tips
    // this will work only for today
    $app->get('/distribution/subscription-restricted-tips', function () use ($app) {

        $data = [];
        $date = gmdate('Y-m-d');

        // get all packages
        $pack = \App\Package::select('id')->get();

        foreach ($pack as $p) {

            // get package associadet events from distribution
            $events = \App\Distribution::where('packageId', $p->id)
                ->where('systemDate', gmdate('Y-m-d'))->get()->toArray();

            // get all subscriptions for package
            $subscriptionInstance = new \App\Http\Controllers\Admin\Subscription();
            $subscriptonsIds = $subscriptionInstance->getSubscriptionsIdsWithNotEnoughTips($p->id);

            foreach ($subscriptonsIds as $subscriptionId) {

                // get all restricted tips for subscription
                $restrictedTips = \App\SubscriptionRestrictedTip::where('subscriptionId', $subscriptionId)
                    ->where('systemDate', $date)->get()->toArray();

                $e = $events;
                foreach ($e as $k => $v) {
                    // set default to false
                    $e[$k]['restricted'] = false;
                    foreach ($restrictedTips as $r) {
                        if ($v['id'] == $r['distributionId'])
                            $e[$k]['restricted'] = true;
                    }
                }

                $subscription = \App\Subscription::find($subscriptionId);

                $data[$subscription->siteId]['siteName'] = \App\Site::find($subscription->siteId)->name;

                $data[$subscription->siteId]['subscriptions'][] = [
                    'id'               => $subscription->id,
                    'siteName'         => \App\Site::find($subscription->siteId)->name,
                    'subscriptionName' => $subscription->name,
                    'customerId'       => $subscription->customerId,
                    'customerEmail'    => \App\Customer::find($subscription->customerId)->email,
                    'totalTips'        => $subscription->tipsLeft - $subscription->tipsBlocked,
                    'totalEvents'      => count($events),
                    'events'           => $e,
                ];
            }
        }

        return response()->json([
            'type' => 'success',
            'date' => gmdate('Y-m-d'),
            'data' => $data,
        ]);
    });

    // Distribution
    // @param string $systemDate
    // @param array $associations
    // 1 - delete all subscription restricted tips for $systemDate
    // 2 - create subscription restricted tips from $associations
    // @return array()
    $app->post('/distribution/subscription-restricted-tips', function (Request $r) use ($app) {
        $systemDate = $r->input('systemDate');
        $restrictions = $r->input('restrictions');

        // TODO
        // check systemDate to be valid

        // delete all restrictions
        \App\SubscriptionRestrictedTip::where('systemDate', $systemDate)->delete();

        // creeate again restrictions
        foreach ($restrictions as $restriction) {
            \App\SubscriptionRestrictedTip::create([
                'subscriptionId' => $restriction['subscriptionId'],
                'distributionId' => $restriction['distributionId'],
                'systemDate'     => $systemDate,
            ]);
        }

        return response()->json([
            'type'    => 'success',
            'message' => 'Success update Manage Users',
        ]);
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
    $app->post('/archive/publish', function(Request $r) use ($app) {
        $ids = $r->input('ids');
        $archive = new \App\Http\Controllers\Admin\Archive();
        return $archive->publish($ids);
    });

    /*
     * Test
     * Here we collect test routes for imap, smtp and other many types
     ---------------------------------------------------------------------*/

    // Test
    // @param int $siteId
    // @param string $email
    // for test smtp connection will create new record in email_schedule table with a test email
    // @return array()
    $app->post('/test/send-test-email/{siteid}', function (Request $r, $siteId) use ($app) {
        $email = $r->input('email');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            return [
                'type' => 'error',
                'message' => 'You must enter a valid email address',
            ];

        $site = \App\Site::find($siteId);
        if ($site == null)
            return [
                'type' => 'error',
                'message' => "Can not find site with id: $siteId",
            ];

        $args = [
            'provider'        => 'site',
            'sender'          => $site->id,
            'type'            => 'testSmpt',
            'identifierName'  => 'siteId',
            'identifierValue' => $site->id,
            'from'            => $site->email,
            'fromName'        => $site->name,
            'to'              => $email,
            'toName'          => $email,
            'subject'         => 'Test smpt for ' . $site->name,
            'body'            => 'This is a test email to check smpt configuration.',
            'status'          => 'waiting',
        ];
        //\App\EmailSchedule::create($args);

        return [
            'type' => 'success',
            'message' => "An emai was scheduled for sendind \n to: $email \n from: $site->name",
        ];
    });

});
