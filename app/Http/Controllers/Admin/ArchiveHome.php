<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ArchiveHome extends Controller
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

    /*
     * @return array()
     */
    public function index(Request $r)
    {
        $data = [];

        $siteId = $r->input('siteId');
        $tableIdentifier = $r->input('tableIdentifier');

        // get events
        $data['events'] = \App\ArchiveHome::where('siteId', $siteId)
            ->where('tableIdentifier', $tableIdentifier)
            ->orderBy('order', 'asc')
            ->get()->toArray();

        // get archive home config
        $data['conf'] = \App\ArchiveHomeConf::where('siteId', $siteId)
            ->where('tableIdentifier', $tableIdentifier)
            ->first();

        return $data;
    }

    // @param integer $id
    // @return event || null
    public function get($id) {
        return \App\ArchiveHome::find($id);
    }

    // @param $id,
    // toogle show/hide an event from archiveHome
    // @return array()
    public  function toogleShowHide($id)
    {
        $event = \App\ArchiveHome::find($id);

        if (!$event)
            return [
                'type' => 'error',
                'message' => "This event not exist anymore!",
            ];

        $event->isVisible ? $event->isVisible = '0' : $event->isVisible = '1';
        $event->save();

        return [
            'type' => 'success',
            'message' =>"Status of event was successfful changed!",
        ];
    }

    // @param array $order
    // This will save modified order for events in archive big
    // @return void
    public function setOrder(Request $r) {
        $order = $r->input('order');

        foreach ($order as $item) {
            $event = \App\ArchiveHome::find($item['id']);
            $event->order = $item['order'];
            $event->update();
        }

        return [
            'type' => 'success',
            'message' =>"Orser was successful changed.",
        ];
    }

    // @ param integer $siteId
    // @ param string $tableIdentifier
    // This will increment order for each event
    // @return void
    public function incrementOrder($siteId, $tableIdentifier) {
        $events = \App\ArchiveHome::where('siteId', $siteId)
            ->where('tableIdentifier', $tableIdentifier)
            ->get();

        foreach ($events as $event) {
            $event->order++;
            $event->update();
        }
    }

    public function saveConfiguration(Request $r) {
        $siteId = $r->input('siteId');
        $tableIdentifier = $r->input('tableIdentifier');
        $eventsNumber = $r->input('eventsNumber');
        $dateStart = $r->input('dateStart');

        $row = \App\ArchiveHomeConf::where('siteId', $siteId)
            ->where('tableIdentifier', $tableIdentifier)
            ->update([
                'eventsNumber' => $eventsNumber,
                'dateStart'    => $dateStart,
            ]);

        // delete events with dateStart less than
        \App\ArchiveHome::where('siteId', $siteId)
            ->where('tableIdentifier', $tableIdentifier)
            ->where('systemDate', '<', $dateStart)
            ->delete();

        // delete in addition events than events number
        $this->deleteInAdditionEvents($siteId, $tableIdentifier);

        return [
            'type' => 'success',
            'message' =>"Configuration was saved.",
        ];
    }

    // @param integer $siteId
    // @param string $tableIdentifier
    // This will delete in addition events than events mumber
    // @return void
    public function deleteInAdditionEvents($siteId, $tableIdentifier) {

        // get max events number
        $maxEventsNumber = \App\ArchiveHomeConf::where('siteId', $siteId)
            ->where('tableIdentifier', $tableIdentifier)
            ->first()->eventsNumber;

        $events = \App\ArchiveHome::orderBy('order', 'asc')
            ->where('siteId', $siteId)
            ->where('tableIdentifier', $tableIdentifier)
            ->skip($maxEventsNumber)
            ->take(1000)
            ->get();

        foreach ($events as $e)
            $e->delete();
    }

    public function store() {}

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
    public function update(Request $r, $id)
    {
        $siteId = $r->input('siteId');
        $country = $r->input('country');
        $league = $r->input('league');
        $stringEventDate = $r->input('stringEventDate');
        $homeTeam = $r->input('homeTeam');
        $awayTeam = $r->input('awayTeam');
        $predictionId = $r->input('predictionId');
        $statusId = $r->input('statusId');

        $event = \App\ArchiveHome::find($id);
        if (!$event)
            return [
                'type' => 'error',
                'message' => "This event not exist anymore!",
            ];

        // get prediction according to site.
        $sitePrediction = \App\SitePrediction::where([
            ['siteId', '=', $siteId],
            ['predictionIdentifier', '=', $predictionId],
        ])->first();

        $event->country = $country;
        $event->league = $league;
        $event->stringEventDate = $stringEventDate;
        $event->homeTeam = $homeTeam;
        $event->awayTeam = $awayTeam;
        $event->predictionName = $sitePrediction->name;
        $event->predictionId = $predictionId;
        $event->statusId = $statusId;
        $event->save();

        return [
            'type' => 'success',
            'message' =>"Archive event was succesfful updated.",
        ];
    }

    public function destroy() {}

}

