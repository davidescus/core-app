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

        $predictions = [
            'team_1',
            'team_2',
            'both_to_score',
            'over_25',
            'over_15',
            'under_15',
            'under_25',
        ];

        for($x = 0; $x < 30; $x++) {

            // set random event date
            $type = rand(0, 1);
            if ($type == 0)
               $eventDate = Carbon::now('UTC')->modify('+' . rand(100, 700) . 'minute');
            else
               $eventDate = Carbon::now('UTC')->modify('-' . rand(105, 700) . 'minute');


            Event::create([
                'source'       => 'tipstersPortal',
                'provider'     => $tipsters[rand(0, 3)],
                'country'      => 'country_' . rand(0, 50),
                'league'       => 'league_' . rand(0, 500),
                'homeTeam'     => 'team_' . rand(0, 5000),
                'awayTeam'     => 'team_' . rand(0, 5000),
                'odd'          => round((rand(130, 250) / 100), 2),
                'predictionId' => $predictions[rand(0, 6)],
                'result'       => $type == 1 ? rand(0, 5) . '-' . rand(0, 5) : '',
                'statusId'     => $type == 1 ? rand(1, 4) : '',
                'eventdate'    => $eventDate,
            ]);
        }
    }
}
