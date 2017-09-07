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

    // create new customer associated with a site
    // @param integer $siteId
    // @param string  $name
    // @param string  $email
    // @param string  $activeEmail
    // @return array()
    public function store(Request $r, $siteId) {

        $site = \App\Site::find($siteId);
        if (!$site)
            return [
                'type' => 'error',
                'message' => "Site id: $siteId not exist anymore."
            ];

        $customer = \App\Customer::create([
            'siteId'        => $siteId,
            'name'          => $r->input('name'),
            'email'         => $r->input('email'),
            'activeEmail'   => $r->input('activeEmail'),
        ]);

        return [
            'type'    => 'success',
            'message' => "Customer was created with success.",
            'data'    => $customer,
        ];
    }

    public function update() {}

    public function destroy() {}

}
