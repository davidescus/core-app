<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Prediction;

class PredictionTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $groups = [
            '1x2' => [
                ['identifier' => 'team1', 'name' => 'Team 1'],
                ['identifier' => 'team2', 'name' => 'Team 2'],
                ['identifier' => 'equal', 'name' => 'Equal'],
            ],
            'g/g' => [
                ['identifier' => 'bothToScore', 'name' => 'Both To Score'],
                ['identifier' => 'oneToScore', 'name' => 'One To Score'],
                ['identifier' => 'noGoal', 'name' => 'No Goal'],
            ],
            'o/u' => [
                ['identifier' => 'over_1.25', 'name' => 'Over 1.25'],
                ['identifier' => 'under_1.25', 'name' => 'Under 1.25'],
                ['identifier' => 'over_1.5', 'name' => 'Over 1.5'],
                ['identifier' => 'under_1.5', 'name' => 'Under 1.5'],
                ['identifier' => 'over_1.75', 'name' => 'Over 1.75'],
                ['identifier' => 'under_1.75', 'name' => 'Under 1.75'],
                ['identifier' => 'over_2', 'name' => 'Over 2'],
                ['identifier' => 'under_2', 'name' => 'Under 2'],
                ['identifier' => 'over_2.25', 'name' => 'Over 2.25'],
                ['identifier' => 'under_2.25', 'name' => 'Under 2.25'],
                ['identifier' => 'over_2.5', 'name' => 'Over 2.5'],
                ['identifier' => 'under_2.5', 'name' => 'Under 2.5'],
                ['identifier' => 'over_2.75', 'name' => 'Over 2.75'],
                ['identifier' => 'under_2.75', 'name' => 'Under 2.75'],
                ['identifier' => 'over_3', 'name' => 'Over 3'],
                ['identifier' => 'under_3', 'name' => 'Under 3'],
                ['identifier' => 'over_3.25', 'name' => 'Over 3.25'],
                ['identifier' => 'under_3.25', 'name' => 'Under 3.25'],
                ['identifier' => 'over_3.5', 'name' => 'Over 3.5'],
                ['identifier' => 'under_3.5', 'name' => 'Under 3.5'],
                ['identifier' => 'over_3.75', 'name' => 'Over 3.75'],
                ['identifier' => 'under_3.75', 'name' => 'Under 3.75'],
            ],
            'ah' => [
                ['identifier' => 'team1-ah_0', 'name' => 'Team 1 0 AH'],
                ['identifier' => 'team2-ah_0', 'name' => 'Team 2 0 AH'],
                ['identifier' => 'team1-ah_+0.5', 'name' => 'Team 1 +0.5 AH'],
                ['identifier' => 'team2-ah_+0.5', 'name' => 'Team 2 +0.5 AH'],
                ['identifier' => 'team1-ah_-0.5', 'name' => 'Team 1 -0.5 AH'],
                ['identifier' => 'team2-ah_-0.5', 'name' => 'Team 2 -0.5 AH'],
                ['identifier' => 'team1-ah_+1', 'name' => 'Team 1 +1 AH'],
                ['identifier' => 'team2-ah_+1', 'name' => 'Team 2 +1 AH'],
                ['identifier' => 'team1-ah_-1', 'name' => 'Team 1 -1 AH'],
                ['identifier' => 'team2-ah_-1', 'name' => 'Team 2 -1 AH'],
                ['identifier' => 'team1-ah_+1.25', 'name' => 'Team 1 +1.25 AH'],
                ['identifier' => 'team2-ah_+1.25', 'name' => 'Team 2 +1.25 AH'],
                ['identifier' => 'team1-ah_-1.25', 'name' => 'Team 1 -1.25 AH'],
                ['identifier' => 'team2-ah_-1.25', 'name' => 'Team 2 -1.25 AH'],
                ['identifier' => 'team1-ah_+1.5', 'name' => 'Team 1 +1.5 AH'],
                ['identifier' => 'team2-ah_+1.5', 'name' => 'Team 2 +1.5 AH'],
                ['identifier' => 'team1-ah_-1.5', 'name' => 'Team 1 -1.5 AH'],
                ['identifier' => 'team2-ah_-1.5', 'name' => 'Team 2 -1.5 AH'],
                ['identifier' => 'team1-ah_+1.75', 'name' => 'Team 1 +1.75 AH'],
                ['identifier' => 'team2-ah_+1.75', 'name' => 'Team 2 +1.75 AH'],
                ['identifier' => 'team1-ah_-1.75', 'name' => 'Team 1 -1.75 AH'],
                ['identifier' => 'team2-ah_-1.75', 'name' => 'Team 2 -1.75 AH'],
                ['identifier' => 'team1-ah_+2', 'name' => 'Team 1 +2 AH'],
                ['identifier' => 'team2-ah_+2', 'name' => 'Team 2 +2 AH'],
                ['identifier' => 'team1-ah_-2', 'name' => 'Team 1 -2 AH'],
                ['identifier' => 'team2-ah_-2', 'name' => 'Team 2 -2 AH'],
                ['identifier' => 'team1-ah_+2.25', 'name' => 'Team 1 +2.25 AH'],
                ['identifier' => 'team2-ah_+2.25', 'name' => 'Team 2 +2.25 AH'],
                ['identifier' => 'team1-ah_-2.25', 'name' => 'Team 1 -2.25 AH'],
                ['identifier' => 'team2-ah_-2.25', 'name' => 'Team 2 -2.25 AH'],
                ['identifier' => 'team1-ah_+2.5', 'name' => 'Team 1 +2.5 AH'],
                ['identifier' => 'team2-ah_+2.5', 'name' => 'Team 2 +2.5 AH'],
                ['identifier' => 'team1-ah_-2.5', 'name' => 'Team 1 -2.5 AH'],
                ['identifier' => 'team2-ah_-2.5', 'name' => 'Team 2 -2.5 AH'],
            ],
        ];

        foreach ($groups as $group => $value) {
            foreach ($value as $v) {
                Prediction::firstOrCreate([
                    'identifier' => $v['identifier'],
                    'name'       => $v['name'],
                    'group'      => $group,
                ]);
            }
        }
    }
}
