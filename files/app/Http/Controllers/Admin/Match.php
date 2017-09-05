<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Match extends Controller
{

    public function index()
    {
    }

    // get match by id
    // @param integer $id
    // @return object
    public function get($id)
    {
        return \App\Match::find($id);
    }

    // get all available matches by search
    // @param string $filter
    // @return array()
    public function getMatchesByFilter($filter)
    {
        return \App\Match::where('country', 'like', '%' . $filter . '%')
            ->orWhere('league', 'like', '%' . $filter . '%')
            ->orWhere('homeTeam', 'like', '%' . $filter . '%')
            ->orWhere('awayTeam', 'like', '%' . $filter . '%')
            ->get();
    }

    public function store() {}

    public function update() {}

    public function destroy() {}

}
