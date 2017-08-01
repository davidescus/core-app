<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\SitePackage;

class SitePackageTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // first we create packages and after we add this connection
        $packages = \App\Prediction::all();

        foreach ($packages as $package) {

            SitePackage::firstOrCreate([
                'siteId'    => $package->siteId,
                'packageId' => $package->id,
            ]);
        }
    }
}
