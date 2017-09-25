<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class Event extends Controller
{

    public function index()
    {
        return \App\Event::all();
    }

    // @retun object event
    public function get($id) {
        return \App\Event::find($id);
    }

    // get all associated events
    // @return array()
    public function getAssociatedEvents() {

        $eventsIds = [];
        $ids = \App\Association::select('eventId')->distinct()->where('eventId', '!=', '0')->get();

        foreach ($ids as $id)
            $eventsIds[] = $id->eventId;

        return \App\Event::whereIn('id', $eventsIds)->get()->toArray();
    }

    public function store() {}

    public function update() {}

    public function destroy() {}

    // add event from match
    // @param integer $matchId
    // @param string  $predictionId
    // @param string  $odd
    // @return array()
    public function createFromMatch(Request $r)
    {
        $matchId = $r->input('matchId');
        $predictionId = $r->input('predictionId');
        $odd = $r->input('odd');

        if (!$predictionId || trim($predictionId) == '-') {
            return [
                'type' => 'error',
                'message' => "Prediction can not be empty!",
            ];
        }

        if (!$odd || trim($odd) == '-') {
            return [
                'type' => 'error',
                'message' => "Odd can not be empty!",
            ];
        }

        $match = \App\Match::find($matchId)->toArray();

        // check if event already exists with same prediciton
        if (\App\Event::where('homeTeamId', $match['homeTeamId'])
            ->where('awayTeamId', $match['awayTeamId'])
            ->where('eventDate', $match['eventDate'])
            ->where('predictionId', $predictionId)
            ->count())
        {
            return [
                'type' => 'error',
                'message' => "This events already exists with same prediction",
            ];
        }

        if (!$match) {
            return [
                'type' => 'error',
                'message' => "Match with id: $matchId not founded!",
            ];
        }

        $match['predictionId'] = $predictionId;
        $match['odd'] = $odd;
        $match['source'] = 'feed';
        $match['provider'] = 'event';

        $event = \App\Event::create($match);

        return [
            'type' => 'success',
            'message' => "Event was creeated with success",
            'data' => $event,
        ];
    }

    // @param integer $id
    // @param string  $result
    // @param integer $statusId
    // @retun array()
    public function updateResultAndStatus(Request $r, $id) {
        $result = $r->input('result');
        $statusId = $r->input('statusId');

        //  TODO check validity of result and status

        $event = \App\Event::find($id);
        if (!$event)
            return [
                'type' => 'error',
                'message' => "This event not exist anymore!",
            ];

        // update event
        $event->result = $result;
        $event->statusId = $statusId;
        $event->save();

        $update = [
            'result' => $result,
            'statusId' => $statusId,
        ];

        // update associations
        \App\Association::where('eventId', $id)->update($update);

        // update distribution
        \App\Distribution::where('eventId', $id)->update($update);

        return [
            'type' => 'success',
            'message' =>"Prediction and status was succesfful updated.",
        ];
    }

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
