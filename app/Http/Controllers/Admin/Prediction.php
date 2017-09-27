<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Prediction extends Controller
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
        $pred = \App\Prediction::all();

        $data = [];
        foreach ($pred as $p) {
            $data[$p['group']]['predictions'][] = $p;
        }

        return $data;
    }

    public function get() {}

    public function store() {}

    public function update() {}

    public function destroy() {}

}
