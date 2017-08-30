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
            'name'       => 'GoForWinners',
            'url'        => 'https://www.goforwinners.com/dev/',
            'isConnect'  => 0,
            'token'      => md5(microtime() . rand(0, 1000)),
        ]);

        Site::firstOrCreate([
            'name'       => 'PopularSoccerTips',
            'isConnect'  => 0,
            'token'      => md5(microtime() . rand(0, 1000)),
        ]);

        Site::firstOrCreate([
            'name'       => 'SoccerTipsArena',
            'isConnect'  => 0,
            'token'      => md5(microtime() . rand(0, 1000)),
        ]);

        Site::firstOrCreate([
            'name'       => 'DailySoccerWins',
            'isConnect'  => 0,
            'token'      => md5(microtime() . rand(0, 1000)),
        ]);

        Site::firstOrCreate([
            'name'       => 'FreshSoccerBets',
            'isConnect'  => 0,
            'token'      => md5(microtime() . rand(0, 1000)),
        ]);
    }
}
