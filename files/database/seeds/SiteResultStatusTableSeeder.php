<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\SiteResultStatus;

class SiteResultStatusTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sites = \App\Site::all();
        $statusName = [
            0 => [
                1 => 'WIN',
                2 => 'LOSS',
                3 => 'DRAW',
                4 => 'POSTP',
            ],
            1 => [
                1 => 'Win',
                2 => 'Loss',
                3 => 'Draw',
                4 => 'Postp',
            ],
            2 => [
                1 => 'win',
                2 => 'loss',
                3 => 'draw',
                4 => 'postp',
            ],
            3 => [
                1 => 'Winner',
                2 => 'Losser',
                3 => 'Draw',
                4 => 'PostP',
            ],
        ];

        foreach ($sites as $site) {

            $statusType = rand(0,3);

            if (!SiteResultStatus::where('siteId', $site->id)->where('statusId', 1)->get())
                SiteResultStatus::create([
                    'siteId'      => $site->id,
                    'statusId'    => 1,
                    'statusName'  => $statusName[$statusType][1],
                    'statusClass' => 'result win-result',
                ]);
            if (!SiteResultStatus::where('siteId', $site->id)->where('statusId', 2)->get())
                SiteResultStatus::create([
                    'siteId'      => $site->id,
                    'statusId'    => 2,
                    'statusName'  => $statusName[$statusType][2],
                    'statusClass' => 'result win-result',
                ]);
            if (!SiteResultStatus::where('siteId', $site->id)->where('statusId', 3)->get())
                SiteResultStatus::create([
                    'siteId'      => $site->id,
                    'statusId'    => 3,
                    'statusName'  => $statusName[$statusType][3],
                    'statusClass' => 'result win-result',
                ]);
            if (!SiteResultStatus::where('siteId', $site->id)->where('statusId', 4)->get())
                SiteResultStatus::create([
                    'siteId'      => $site->id,
                    'statusId'    => 4,
                    'statusName'  => $statusName[$statusType][4],
                    'statusClass' => 'result win-result',
                ]);
        }
    }
}
