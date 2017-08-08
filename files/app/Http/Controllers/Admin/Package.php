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
        $packages = \App\Package::where('siteId', $siteId)->get();

        $predictions = \App\Prediction::all();

        //iterate al packages and get predictions
        foreach ($packages as $k => $package) {

            // get allowed predictions
            $allowedPredictions = \App\PackagePrediction::where('packageId', $package['id'])->get();

            $pred = $predictions;
            foreach ($pred as $kp => $p) {

                // make default association false
                $pred[$kp]['isAssociated'] = false;
                foreach ($allowedPredictions as $kap => $ap) {

                    // if association exists make it true
                    if ($p->identifier === $ap->predictionIdentifier)
                        $pred[$kp]['isAssociated'] = true;
                }
            }

            // create new array and sort predicitons
            $data = [];
            foreach ($pred as $p) {
               $data[$p->group][] = $p;
            }

            $packages[$k]['predictions'] = $data;
        }

        return $packages;
    }

}
