<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SitePackage extends Controller
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
    public function index()
    {
    }

    /*
     * return object
     */
    public function get() {}

    /*
     * return array()
     */
    public function storeIfNotExists(Request $r)
    {
        $d = $r->input('data');

        if(!$d)
            return response()->json([
                "type" => "error",
                "message" => "Invalid data for association.",
            ]);

        if (\App\SitePackage::where('siteId', $d['siteId'])->where('packageId', $d['packageId'])->count())
            return response()->json([
                "type" => "success",
                "message" => "Package: " . $d['packageId'] . " already associated with site: " . $d['siteId'],
            ]);

        \App\SitePackage::create($d);

        return response()->json([
            "type" => "success",
            "message" => "Package: " . $d['packageId'] . " successfful associated with site: " . $d['siteId'],
        ]);
    }

    public function update() {}

    public function destroy() {}
}
