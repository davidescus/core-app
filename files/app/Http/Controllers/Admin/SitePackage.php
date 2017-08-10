<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SitePackage extends Controller
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
    public function storeIfNotExists(Request $r)
    {
        $data = $r->input('data');

        return $data;
    }

    public function update() {}

    public function destroy() {}
}
