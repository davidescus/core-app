<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

    // @param integer $id
    // @return event || null
    public function get($id) {
        return \App\ArchiveBig::find($id);
    }


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

    // @param $siteId,
    // @param $date format: Y-m,
    // set isPublishInSite 1 for all events fron site in selected month
    // @return array()
    public function publishMonth(Request $r)
    {
        $date   = $r->input('date');
        $siteId = $r->input('siteId');

        $first = $date . '-01';

        $carbon = Carbon::parse($first);
        $last = $carbon->endOfMonth()->toDateString();

        \App\ArchiveBig::where('siteId', $siteId)
            ->where('systemDate', '>=', $first)
            ->where('systemDate', '<=', $last)
            ->update(['isPublishInSite' => '1']);

        return [
            'type' => 'success',
            'message' =>"Events beetwen $first - $last was published in site: $siteId",
        ];


    }

    // get array with available years and month based on archived events.
    // @return array()
    public function getAvailableMounths()
    {
        $dates = [];

        $first = \App\ArchiveBig::select(\DB::raw('min(systemDate) as systemDate'))->get()[0]->systemDate;
        $last = \App\ArchiveBig::select(\DB::raw('max(systemDate) as systemDate'))->get()[0]->systemDate;

        $start    = (new \DateTime($first))->modify('first day of this month');
        $end      = (new \DateTime($last))->modify('first day of next month');
        $interval = \DateInterval::createFromDateString('1 month');
        $period   = new \DatePeriod($start, $interval, $end);

        foreach ($period as $dt) {
            $dates[] = [
                'year' => $dt->format("Y"),
                'month' => $dt->format("m"),
            ];
        }

        return array_reverse($dates);
    }

    // @param $id,
    // toogle show/hide an event from archiveBig
    // @return array()
    public  function toogleShowHide($id)
    {
        $event = \App\ArchiveBig::find($id);

        if (!$event)
            return [
                'type' => 'error',
                'message' => "This event not exist anymore!",
            ];

        $event->isVisible ? $event->isVisible = '0' : $event->isVisible = '1';
        $event->save();

        return [
            'type' => 'success',
            'message' =>"Status of event was successfful changed!",
        ];
    }

    public function store() {}

    public function update() {}

    // @param $id,
    // @param $siteId,
    // @param $predictionId,
    // @param $StatusId,
    // update prediction and status
    // @return array()
    public function updatePredictionAndStatus(Request $r, $id)
    {
        $siteId = $r->input('siteId');
        $predictionId = $r->input('predictionId');
        $statusId = $r->input('statusId');

        $event = \App\ArchiveBig::find($id);
        if (!$event)
            return [
                'type' => 'error',
                'message' => "This event not exist anymore!",
            ];

        // get prediction according to site.
        $sitePrediction = \App\SitePrediction::where([
            ['siteId', '=', $siteId],
            ['predictionIdentifier', '=', $predictionId],
        ])->first();

        $event->predictionName = $sitePrediction->name;
        $event->predictionId = $predictionId;
        $event->statusId = $statusId;
        $event->save();

        return [
            'type' => 'success',
            'message' =>"Prediction and status was succesfful updated.",
        ];
    }

    public function destroy() {}

}
