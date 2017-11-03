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
        $siteId = $r->input('siteId');
        $tableIdentifier = $r->input('tableIdentifier');

        return \App\ArchiveHome::where('siteId', $siteId)
            ->where('tableIdentifier', $tableIdentifier)
            ->get()->toArray();
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

    public function store() {}

    public function update() {}

    public function destroy() {}

}

