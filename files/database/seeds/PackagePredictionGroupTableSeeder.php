
<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\PackagePredictionGroup;

class PackagePredictionGroupTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PackagePredictionGroup::create([
            'packageId'       => 1,
            'predictionGroup' => '1x2',
        ]);
    }
}
