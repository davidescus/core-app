<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Distribution extends Controller
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
     * @string $date format: Y-m-d || 0
     * @return array()
     */
    public function index($date = null)
    {
        $data = [];

        // default set current date GMT
        if ($date === null || $date == 0)
            $date = gmdate('Y-m-d');

        foreach (\App\Site::all() as $site) {
            // set siteName
            $data[$site->id]['name'] = $site->name;
            $data[$site->id]['siteId'] = $site->id;

            // get associated packages frm site_package
            $assocPacks = \App\SitePackage::select('packageId')->where('siteId', $site->id)->get()->toArray();
            foreach ($assocPacks as $assocPack) {
                // get package
                $package = \App\Package::find($assocPack['packageId']);

                // get events for package
                $distributedEvents = \App\Distribution::where('packageId', $package->id)->where('systemdate', $date)->get();

                $data[$site->id]['packages'][$assocPack['packageId']]['id'] = $package->id;
                $data[$site->id]['packages'][$assocPack['packageId']]['name'] = $package->name;
                $data[$site->id]['packages'][$assocPack['packageId']]['tipsPerDay'] = $package->tipsPerDay;
                $data[$site->id]['packages'][$assocPack['packageId']]['eventsNumber'] = count($distributedEvents);
                $data[$site->id]['packages'][$assocPack['packageId']]['events'] = $distributedEvents;
            }
        }

        return $data;
    }

    public function get() {}

    public function store() {}

    public function update() {}

    public function destroy() {

    }
}
