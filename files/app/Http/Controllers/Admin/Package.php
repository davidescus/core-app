<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Package extends Controller
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

    public function index()
    {
        return \App\Package::all();
    }

    /*
     * return object
     */
    public function get($id) {
        return \App\Package::find($id);
    }

    public function store() {}

    public function update() {}

    public function destroy() {}

    /*
     * @return array()
     */
    public function getPackagesBySite($siteId)
    {
        return \App\Package::where('siteId', $siteId)->get();
    }

}
