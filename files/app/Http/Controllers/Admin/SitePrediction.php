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

            if ($sitePrediction) {
                $predictions[$key]['siteName'] = $sitePrediction['name'];
            }
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

    public function store() {}

    public function update() {}

    public function destroy() {}
}
