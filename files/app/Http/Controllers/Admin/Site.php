<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Site extends Controller
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
        return \App\Site::all();
    }

    /*
     * return object
     */
    public function get($id) {
        return \App\Site::find($id);
    }

    public function store(Request $r) {

        // TODO: check if new name is valid
        $name = $r->input('name');

        // Site name must be unique
        $site = \App\Site::where('name', $name)->first();
        if ($site !== null) {
            return response()->json([
                "type" => "error",
                "message" => "This site already exists!"
            ]);
        }

        $site = \App\Site::create([
            "name" => $name
        ]);

        return response()->json([
            "type" => "success",
            "message" => "New site was added with success!",
            "data" => \App\Site::where('name', $name)->first(),
        ]);
    }

    public function update(Request $r, $id) {
        $site = \App\Site::find($id);

        // Site not exists retur status not exists
        if ($site === null) {
            return response()->json([
                "type" => "error",
                "message" => "Site with id: $id not exists"
            ]);
        }

        $where = [
            ['name', '=', $r->input('name')],
            ['id', '!=', $id],
        ];
        if (\App\Site::where($where)->count())
            return response()->json([
                "type" => "error",
                "message" => "Site with name: " . $r->input('name') . " already exists!",
            ]);

        if ($site->name !== $r->input('name')) {
            $site->name = $r->input('name');
            $site->save();
        }

        return response()->json([
            "type" => "success",
            "message" => "General site information was updated with success!"
        ]);
    }

    public function destroy() {}

    /*
     * @return array()
     */
    public function getIdsAndNames()
    {
        return \App\Site::select('id', 'name')->get();
    }

}
