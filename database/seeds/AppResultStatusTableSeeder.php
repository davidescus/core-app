<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\AppResultStatus;

class AppResultStatusTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AppResultStatus::firstOrCreate([
            'name' => 'Win',
        ]);
        AppResultStatus::firstOrCreate([
            'name' => 'Loss',
        ]);
        AppResultStatus::firstOrCreate([
            'name' => 'Draw',
        ]);
        AppResultStatus::firstOrCreate([
            'name' => 'PostP',
        ]);
    }
}
