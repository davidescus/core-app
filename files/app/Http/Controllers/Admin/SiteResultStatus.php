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
        return \App\SiteResultStatus::where('siteId', $siteId)->get();
    }

    /*
     * return object
     */
    public function get() {}

    public function store() {}

    public function update() {}

    public function destroy() {}
}
