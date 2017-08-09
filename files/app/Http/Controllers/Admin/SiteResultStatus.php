<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SiteResultStatus extends Controller
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
     * @return collection of arrays
     */
    public function index($siteId)
    {
        $status = \App\SiteResultStatus::where('siteId', $siteId)->get();

        // create index array as statusId
        $data = [];
        foreach ($status as $s) {
            $data[$s->statusId] = $s;
        }

        return $data;
    }

    /*
     * return object
     */
    public function get() {}

    public function store() {}

    public function update() {}

    public function destroy() {}
}
