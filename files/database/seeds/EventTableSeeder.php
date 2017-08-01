<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Event;

class EventTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gmtDate = gmdate('Y-m-d H:i:s');

        $tipsters = [
            'Luca Hubber',
            'Patrick Nickolas',
            'Daniel Watson',
            'David Kraig',
        ];

        $predictions = \App\Prediction::all();

        for($x = 0; $x < 50; $x++) {

            // set random event date
            $type = rand(0, 1);
            if ($type == 0)
                $eventDate = Carbon::now('UTC')->modify('+' . rand(100, 700) . 'minute');
            else {
                $day = rand(0, 2);
                $eventDate = Carbon::now('UTC')->modify('-' . rand(105, 700) . 'minute')->modify('-' . $day . 'day');
            }


            Event::create([
                'source'       => 'tipstersPortal',
                'provider'     => $tipsters[rand(0, 3)],
                'country'      => 'country_' . rand(0, 50),
                'league'       => 'league_' . rand(0, 500),
                'homeTeam'     => 'team_' . rand(0, 5000),
                'awayTeam'     => 'team_' . rand(0, 5000),
                'odd'          => round((rand(130, 250) / 100), 2),
                'predictionId' => $predictions[rand(0, count($predictions) - 1)]->identifier,
                'result'       => $type == 1 ? rand(0, 5) . '-' . rand(0, 5) : '',
                'statusId'     => $type == 1 ? rand(1, 4) : '',
                'eventdate'    => $eventDate,
            ]);
        }
    }
}
