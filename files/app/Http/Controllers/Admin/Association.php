<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Association extends Controller
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
    public function index($tableIdentifier, $dateModifier)
    {
        $where[] = ['type', '=', $tableIdentifier];

        if ($dateModifier != '0')
            $where[] = ['systemdate', '=', gmdate('Y-m-d', strtotime($dateModifier))];

        return \App\Association::where($where)->get();
    }

    public function get() {}

    public function store() {}

    public function update() {}

    public function destroy($id) {

        $association = \App\Association::find($id);

        // Site not exists retur status not exists
        if ($association === null) {
            return response()->json([
                "type" => "error",
                "message" => "Event with id: $id not exists"
            ]);
        }

        // could not delete an already distributed association
        if (\App\Distribution::where('associationId', $id)->count())
        return response()->json([
            "type" => "error",
            "message" => "Before delete event: $id  you must delete all distribution of this!"
        ]);

        $association->delete();
        return response()->json([
            "type" => "success",
            "message" => "Site with id: $id was deleted with success!"
        ]);
    }
}
