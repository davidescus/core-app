<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArchiveBig extends Controller
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

    public function get() {}


    // @param integer $siteId
    // @param string $table
    // @param string $date
    // @return array()
    public function getMonthEvents(Request $r)
    {
        $siteId = $r->input('siteId');
        $tableIdentifier = $r->input('tableIdentifier');
        $date = $r->input('date');

        return \App\ArchiveBig::where('siteId', $siteId)
            ->where('tableIdentifier', $tableIdentifier)
            ->where('systemDate', '>=', $date . '-01')
            ->where('systemDate', '<=', $date . '-31')->get()->toArray();
    }

    public function store() {}

    public function update() {}

    public function destroy() {}

}
