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
        if (! Site::where('name', 'GoForwinners')->count()) {
            Site::create([
                'name'       => 'GoForWinners',
                'url'        => 'https://www.goforwinners.com/',
                'isConnect'  => 0,
                'token'      => md5(microtime() . rand(0, 1000)),
                'dateFormat' => 'Y/m/d',
            ]);
        }

        if (! Site::where('name', 'PopularSoccerTips')->count()) {
            Site::create([
                'name'       => 'PopularSoccerTips',
                'isConnect'  => 0,
                'token'      => md5(microtime() . rand(0, 1000)),
                'dateFormat' => 'Y/m/d',
            ]);
        }

        if (! Site::where('name', 'SoccerTipsArena')->count()) {
            Site::create([
                'name'       => 'SoccerTipsArena',
                'isConnect'  => 0,
                'token'      => md5(microtime() . rand(0, 1000)),
                'dateFormat' => 'Y/m/d',
            ]);
        }

        if (! Site::where('name', 'DailySoccerWins')->count()) {
            Site::create([
                'name'       => 'DailySoccerWins',
                'isConnect'  => 0,
                'token'      => md5(microtime() . rand(0, 1000)),
                'dateFormat' => 'Y/m/d',
            ]);
        }

        if (! Site::where('name', 'FreshSoccerBets')->count()) {
            Site::create([
                'name'       => 'FreshSoccerBets',
                'isConnect'  => 0,
                'token'      => md5(microtime() . rand(0, 1000)),
                'dateFormat' => 'Y/m/d',
            ]);
        }
    }
}
