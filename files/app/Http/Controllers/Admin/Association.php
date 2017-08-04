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

    public function index($tableIdentifier)
    {
        return \App\Association::where([['type', '=', $tableIdentifier]])->get();
    }

    public function get() {}

    public function store() {}

    public function update() {}

    public function destroy() {}
}
