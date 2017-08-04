<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

    /*
     * @return array()
     */
    public function getTablesFiltersValues($table)
    {
        $data = [
            'tipsters' => [],
            'leagues'  => []
        ];

        if ($table == 'run' || $table == 'ruv') {
            $data['tipsters'] = \App\Event::distinct()->select('provider')
                ->where('eventDate', '>', Carbon::now('UTC')->addMinutes(20))
                ->groupBy('provider')->get();

            $data['leagues'] = \App\Event::distinct()->select('league')
                ->where('eventDate', '>', Carbon::now('UTC')->addMinutes(20))
                ->groupBy('league')->get();
        }

        if ($table == 'nun' || $table == 'nuv') {
            $data['tipsters'] = \App\Event::distinct()->select('provider')
                ->where([
                    ['eventDate', '<', Carbon::now('UTC')->modify('-105 minutes')],
                        ['result', '<>', ''],
                        ['statusId', '<>', '']
                    ])->groupBy('provider')->get();

            $data['leagues'] = \App\Event::distinct()->select('league')
                ->where([
                    ['eventDate', '<', Carbon::now('UTC')->modify('-105 minutes')],
                        ['result', '<>', ''],
                        ['statusId', '<>', '']
                    ])->groupBy('league')->get();
        }

        return $data;

    }

    /*
     * @return int
     */
    public function getNumberOfAvailableEvents(Request $request)
    {
        $nr = \App\Event::where($this->whereForAvailableEvents($request))->count();
        return $nr ? $nr : 0;
    }

    /*
     * @return array()
     */
    public function getAvailableEvents(Request $request)
    {
        return \App\Event::where($this->whereForAvailableEvents($request))->orderBy('eventDate', 'desc')->get();
    }

    /*
     * @return array() of filters for eloquent
     */
    private function whereForAvailableEvents(Request $request)
    {
        $where = [];
        if ($request->get('provider'))
            $where[] = ['provider', '=', $request->get('provider')];

        if ($request->get('league'))
            $where[] = ['league', '=', $request->get('league')];

        if ($request->get('minOdd'))
            $where[] = ['odd', '>=', $request->get('minOdd')];

        if ($request->get('maxOdd'))
            $where[] = ['odd', '<=', $request->get('maxOdd')];

        if ($request->get('table') == 'run' || $request->get('table')== 'ruv')
            $where[] = ['eventDate', '>', Carbon::now('UTC')->addMinutes(20)];

        if ($request->get('table') == 'nun' || $request->get('table') == 'nuv') {
            $where[] = ['eventDate', '<', Carbon::now('UTC')->modify('-105 minutes')];
            $where[] = ['result', '<>', ''];
            $where[] = ['statusId', '<>', ''];
        }

        return $where;
    }
}
