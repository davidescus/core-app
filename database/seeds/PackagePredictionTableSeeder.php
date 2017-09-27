
<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\PackagePrediction;

class PackagePredictionTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $packages = \App\Package::all();
        $predictions = \App\Prediction::all();

        foreach ($packages as $pk) {
            foreach ($predictions as $pred) {
                PackagePrediction::firstOrCreate([
                    'packageId'       => $pk->id,
                    'predictionIdentifier' => $pred->identifier,
                ]);
            }
        }

        /*
        PackagePrediction::firstOrCreate([
            'packageId'       => 1,
            'predictionIdentifier' => 'over_15',
        ]);

        PackagePrediction::firstOrCreate([
            'packageId'       => 2,
            'predictionIdentifier' => 'under_25',
        ]);

        PackagePrediction::firstOrCreate([
            'packageId'       => 3,
            'predictionIdentifier' => 'over_15',
        ]);

        PackagePrediction::firstOrCreate([
            'packageId'       => 3,
            'predictionIdentifier' => 'over_15',
        ]);

        PackagePrediction::firstOrCreate([
            'packageId'       => 4,
            'predictionIdentifier' => 'over_15',
        ]);

        PackagePrediction::firstOrCreate([
            'packageId'       => 5,
            'predictionIdentifier' => 'over_15',
        ]);

        PackagePrediction::firstOrCreate([
            'packageId'       => 6,
            'predictionIdentifier' => 'over_15',
        ]);

        PackagePrediction::firstOrCreate([
            'packageId'       => 7,
            'predictionIdentifier' => 'over_15',
        ]);

        PackagePrediction::firstOrCreate([
            'packageId'       => 8,
            'predictionIdentifier' => 'over_15',
        ]);

        PackagePrediction::firstOrCreate([
            'packageId'       => 9,
            'predictionIdentifier' => 'over_15',
        ]);

        PackagePrediction::firstOrCreate([
            'packageId'       => 10,
            'predictionIdentifier' => 'over_15',
        ]);

        PackagePrediction::firstOrCreate([
            'packageId'       => 11,
            'predictionIdentifier' => 'over_15',
        ]);
         */
    }
}
