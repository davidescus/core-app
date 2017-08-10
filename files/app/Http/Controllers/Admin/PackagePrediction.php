
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PackagePrediction extends Controller
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
    public function deleteAndStore(Request $r)
    {
        $p = $r->input('data');

        if(!$p)
            return response()->json([
                "type" => "error",
                "message" => "Invalid data for association package with predictions.",
            ]);

        // delete all associated predictions
        \App\PackagePrediction::delete()->where('packageId', $p[0]['packageId']);

        foreach ($p as $v)
            \App\PackagePrediction::create($v);

        return response()->json([
            "type" => "success",
            "message" => "Success associate prediction with package: " . $p[0]['packageId'],
        ]);
    }

    public function update() {}

    public function destroy() {}
}
