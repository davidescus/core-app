<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\SitePrediction;

class SitePredictionTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $predictions = \App\Prediction::all();
        $sites = \App\Site::all();

        foreach ($sites as $site) {
            foreach ($predictions as $prediction) {

                SitePrediction::firstOrCreate([
                    'siteId'               => $site->id,
                    'predictionIdentifier' => $prediction->identifier,
                    'name'                 => str_replace('_', ' ', $prediction->identifier) . '-' . $site->name,
                ]);
            }
        }
    }
}
