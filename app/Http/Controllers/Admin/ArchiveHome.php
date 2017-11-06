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

    public function store() {}

    public function update() {}

    public function destroy() {}

}

