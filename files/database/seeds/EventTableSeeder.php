<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
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

        for($x = 0; $x < 500; $x++) {

            // set random event date
            $eventDate = date('Y-m-d H:i:s', strtotime($gmtDate . '+' . rand(100, 700) . 'minute'));

            Event::create([
                'source'       => 'tipstersPortal',
                'provider'     => $tipsters[rand(0, 3)],
                'country'      => 'country_' . rand(0, 50),
                'league'       => 'league_' . rand(0, 500),
                'homeTeam'     => 'team_' . rand(0, 5000),
                'awayTeam'     => 'team_' . rand(0, 5000),
                'odd'          => rand(1.50, 2.50),
                'predictionId' => $predictions[rand(0, 6)],
                'result'       => rand(0, 5) . '-' . rand(0, 5),
                'statusId'     => rand(1, 4),
                'eventdate'    => $eventDate,
            ]);
        }
    }
}
