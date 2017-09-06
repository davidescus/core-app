<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Customer extends Controller
{

    public function index() {}

    public function get() {}

    // get all customers from a site filtering email
    // @param integer $siteId
    // @param string  $filter
    // @return array()
    public function getCustomersByFilter($siteId, $filter)
    {
        return \App\Customer::where('siteId', $siteId)
            ->where('email', 'like', '%' . $filter . '%')->get()->toArray();
    }

    public function store() {}

    public function update() {}

    public function destroy() {}

}
