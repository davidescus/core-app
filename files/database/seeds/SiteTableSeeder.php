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
        Site::create([
            'name'       => 'PayForTips',
        ]);

        Site::create([
            'name'       => 'PopularSoccerTips',
        ]);

        Site::create([
            'name'       => 'SoccerTipsArena',
        ]);

        Site::create([
            'name'       => 'DailySoccerWins',
        ]);

        Site::create([
            'name'       => 'FreshSoccerBets',
        ]);
    }
}
