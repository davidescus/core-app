<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class Package extends Controller
{

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

    // get ids and names for all pacckage associated with site
    // @param integer $siteId
    // @return array()
    public function getPackagesIdsAndNamesBySite($siteId)
    {
        return \App\Package::select('id', 'name')->where('siteId', $siteId)->get()->toArray();
    }

    /*
     * @return array()
     */
    public function store(Request $r) {

        // Todo: check inputs for validity

        $pack = \App\Package::create($r->all());
        return response()->json([
            "type" => "success",
            "message" => "New package: " . $r->input('name') . " was added with success!",
            "data" => $pack,
        ]);
    }

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
            "message" => "Package information " . $r->input('data')['name'] . " was updated with success!"
        ]);
    }

    /*
     * @return array()
     */
    public function destroy() {
        $pack = \App\Package::find($id);

        // Package not exists retur status not exists
        if ($pack === null) {
            return response()->json([
                "type" => "error",
                "message" => "Package with id: $id not exists"
            ]);
        }
        $pack->delete();

        // delete association with site
        \App\SitePackage::where('packageId', $id)->delete();

        // delete associated predictions
        \App\PackagePrediction::where('packageId', $id)->delete();

        return response()->json([
            "type" => "success",
            "message" => "Pack with id: $id was deleted with success!"
        ]);
    }

    /*
     * @return array()
     */
    public function getPackagesBySite($siteId)
    {
        $packages = \App\Package::where('siteId', $siteId)->get()->toArray();

        $predictions = \App\Prediction::all()->toArray();

        //iterate al packages and get predictions
        foreach ($packages as $k => $package) {

            // get allowed predictions
            $allowedPredictions = \App\PackagePrediction::where('packageId', $package['id'])->get()->toArray();

            $pred = $predictions;
            foreach ($pred as $kp => $p) {

                // make default association false
                $pred[$kp]['isAssociated'] = false;
                foreach ($allowedPredictions as $kap => $ap) {

                    // if association exists make it true
                    if ($p['identifier'] == $ap['predictionIdentifier'])
                       $pred[$kp]['isAssociated'] = true;
                }
            }

            // create new array and sort predicitons
            $data = [];
            foreach ($pred as $p) {
               $data[$p['group']]['predictions'][] = $p;
            }

            $packages[$k]['associatedPredictions'] = $data;
            //$packages[$k]['associatedPredictions'] = $allowedPredictions;

        }

        return $packages;
    }

}
