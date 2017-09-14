<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArchiveBig extends Controller
{

    // @param integer $id
    // get archive-big events for site.
    // @return array() indexed by table idintifier.
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

        $events = \App\ArchiveBig::where('siteId', $id)
            ->where('isPublishInSite', '1')
            ->orderBy('systemDate', 'asc')->get()->toArray();

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
            $year  = date('Y', strtotime($e['systemDate']));
            $month = date('m', strtotime($e['systemDate']));
            $data[$table][$year][$month][] = $e;
        }

        return $data;
    }

    public function get() {}

    public function store() {}

    public function update() {}

    public function destroy() {}

}
