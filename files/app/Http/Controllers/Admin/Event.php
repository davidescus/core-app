<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class Event extends Controller
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
        return \App\Event::all();
    }

    public function get() {}

    public function store() {}

    public function update() {}

    public function destroy() {}
}
