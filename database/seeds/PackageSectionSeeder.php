<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PackageSectionSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $packages = \App\Package::all();

        foreach ($packages as $p) {

            // check if exists records in packageSection
            $existsRecords = \App\PackageSection::where('packageId', $p->id)
                ->where('systemDate', '<=', gmdate('Y-m-d'))->count();

            if (! $existsRecord) {
                \App\PackageSection::create([
                    'packageId'        => $p->id,
                    'section'          => 'nu',
                    'systemDate'       => gmdate('Y-m-d'),
                ]);
            }
        }
    }
}

