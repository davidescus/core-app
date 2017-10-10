<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (getenv('APP_ENV') === 'local') {
            $this->call('AppResultStatusTableSeeder');
            $this->call('PredictionTableSeeder');
            $this->call('SiteTableSeeder');
            $this->call('PackageTableSeeder');
            $this->call('PackagePredictionTableSeeder');
            $this->call('SitePredictionTableSeeder');
            $this->call('SitePackageTableSeeder');
            $this->call('SiteResultStatusTableSeeder');
            $this->call('UserTableSeeder');
        }

        if (getenv('APP_ENV') === 'production') {
            $this->call('AppResultStatusTableSeeder');
            $this->call('PredictionTableSeeder');
            $this->call('UserTableSeeder');
        }
    }
}
