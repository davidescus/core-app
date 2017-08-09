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

    public function storeOrUpdate(Request $r, $siteId) {

        $data = $r->input('data');
        foreach ($data as $s) {
            $row = \App\SiteResultStatus::where('siteId', $siteId)->where('statusId', $s['statusId'])->first();

            if ($row) {
                $row->statusName = $s['statusName'];
                $row->statusClass = $s['statusClass'];
                $row->update();
                continue;
            }

            $s['siteId'] = $siteId;
            \App\SiteResultStatus::create($s);
        }

        return response()->json([
            "type" => "success",
            "message" => "Results names and classes was update with success.",
        ]);

    }

    public function destroy() {}
}
