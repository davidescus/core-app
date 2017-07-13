<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Site;

class SiteTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // insert automaticaly 5 sites in app
        Site::firstOrCreate([
            'name'       => 'PayForTips',
        ]);

        Site::firstOrCreate([
            'name'       => 'PopularSoccerTips',
        ]);

        Site::firstOrCreate([
            'name'       => 'SoccerTipsArena',
        ]);

        Site::firstOrCreate([
            'name'       => 'DailySoccerWins',
        ]);

        Site::firstOrCreate([
            'name'       => 'FreshSoccerBets',
        ]);
    }
}
