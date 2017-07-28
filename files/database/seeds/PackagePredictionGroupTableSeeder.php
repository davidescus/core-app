
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
        PackagePredictionGroup::firstOrCreate([
            'packageId'       => 1,
            'predictionGroup' => '1x2',
        ]);

        PackagePredictionGroup::firstOrCreate([
            'packageId'       => 2,
            'predictionGroup' => 'o/u',
        ]);

        PackagePredictionGroup::firstOrCreate([
            'packageId'       => 3,
            'predictionGroup' => '1x2',
        ]);

        PackagePredictionGroup::firstOrCreate([
            'packageId'       => 4,
            'predictionGroup' => 'o/u',
        ]);

        PackagePredictionGroup::firstOrCreate([
            'packageId'       => 5,
            'predictionGroup' => '1x2',
        ]);

        PackagePredictionGroup::firstOrCreate([
            'packageId'       => 6,
            'predictionGroup' => 'o/u',
        ]);

        PackagePredictionGroup::firstOrCreate([
            'packageId'       => 7,
            'predictionGroup' => '1x2',
        ]);

        PackagePredictionGroup::firstOrCreate([
            'packageId'       => 8,
            'predictionGroup' => 'o/u',
        ]);

        PackagePredictionGroup::firstOrCreate([
            'packageId'       => 9,
            'predictionGroup' => '1x2',
        ]);

        PackagePredictionGroup::firstOrCreate([
            'packageId'       => 10,
            'predictionGroup' => 'o/u',
        ]);

        PackagePredictionGroup::firstOrCreate([
            'packageId'       => 11,
            'predictionGroup' => '1x2',
        ]);
    }
}
