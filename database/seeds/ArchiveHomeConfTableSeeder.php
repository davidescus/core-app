<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\ArchiveHomeConf;

class ArchiveHomeConfTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $packages = \App\Package::all();
        foreach($packages as $p) {
            $confExists = ArchiveHomeConf::where('siteId', $p->siteId)
                ->where('tableIdentifier', $p->tableIdentifier)
                ->count();

            if (! $confExists) {
                ArchiveHomeConf::create([
                    'siteId'          => $p->siteId,
                    'tableIdentifier' => $p->tableIdentifier,
                    'eventsNumber'    => 100,
                    'dateStart'       => '2017-01-01',
                ]);
            }
        }
    }
}

