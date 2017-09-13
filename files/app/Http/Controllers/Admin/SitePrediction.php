<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SitePrediction extends Controller
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
    public function index($siteId)
    {
        $predictions = \App\Prediction::all()->toArray();

        foreach ($predictions as $key => $prediction) {
            $sitePrediction = \App\SitePrediction::where('predictionIdentifier', $prediction['identifier'])->where('siteId', $siteId)->first();

            // set predictioon according to site or default
            $predictions[$key]['siteName'] =  $sitePrediction ? $sitePrediction['name'] : $predictions[$key]['name'];
        }

        // sort predictions by group
        $data = [];
        foreach ($predictions as $p) {
            $data[$p['group']]['predictions'][] = $p;
        }

        return $data;
    }

    /*
     * return object
     */
    public function get() {}

    /*
     * return array()
     */
    public function storeOrUpdate(Request $r, $siteId) {

        $data = $r->input('data');
        foreach ($data as $p) {
            $row = \App\SitePrediction::where('siteId', $siteId)->where('predictionIdentifier', $p['predictionIdentifier'])->first();

            if ($row) {
                $row->name = $p['name'];
                $row->update();
                continue;
            }

            $p['siteId'] = $siteId;
            \App\SitePrediction::create($p);
        }

        return response()->json([
            "type" => "success",
            "message" => "Predictions names was update with success.",
        ]);
    }

    public function update() {}

    public function destroy() {}
}
