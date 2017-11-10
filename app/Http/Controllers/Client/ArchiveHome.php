<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArchiveHome extends Controller
{

    public function index($id)
    {
        $site = \App\Site::find($id);
        if (!$site)
            return false;

        // results name and class
        $results = [];
        foreach (\App\SiteResultStatus::where('siteId', $id)->get()->toArray() as $k => $v)
           $results[$v['statusId']] = $v;

        // prediction
        $predictions = [];
        foreach (\App\SitePrediction::where('siteId', $id)->get()->toArray() as $k => $v)
           $predictions[$v['predictionIdentifier']] = $v;

        $events = \App\ArchiveHome::where('siteId', $id)
            ->where('isVisible', '1')
            ->orderBy('order', 'asc')->get()->toArray();

        $vipFlags = [];

        $data = [];
        foreach ($events as $e) {

            // add result statusName and statusClass and predictionName
            // only when event is tip (NOT noTip)
            if (!$e['isNoTip']) {
                $e['statusName'] = $results[$e['statusId']]['statusName'];
                $e['statusClass'] = $results[$e['statusId']]['statusClass'];
                $e['predictionName'] = $predictions[$e['predictionId']]['name'];
            }

            // vip flag
            if (!isset($vipFlags[$e['packageId']])) {
                $package = \App\Package::find($e['packageId']);
                $vipFlags[$e['packageId']] = $package->vipFlag;
            }
            $e['vipFlag'] = $vipFlags[$e['packageId']];


            $table = $e['tableIdentifier'];
            $data[$table][] = $e;
        }

        return $data;
    }

    public function get() {}

    public function store() {}

    public function update() {}

    public function destroy() {}

}
