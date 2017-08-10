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

    /*
     * @return array()
     */
    public function update(Request $r, $id) {

        $pack = \App\Package::find($id);

        // Package not exists retur status not exists
        if ($pack === null) {
            return response()->json([
                "type" => "error",
                "message" => "Package with id: $id not exists anymore"
            ]);
        }

        // Todo: check inputs for validity
        $pack->update($r->input('data'));
        return response()->json([
            "type" => "success",
            "message" => "Package information was updated with success!"
        ]);
    }

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
               $data[$p->group]['predictions'][] = $p;
            }

            $packages[$k]['associatedPredictions'] = $data;
        }

        return $packages;
    }

}
