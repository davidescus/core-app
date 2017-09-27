<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;

class UserTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // insert automaticaly 5 sites in app
        User::firstOrCreate([
            'name'       => 'Dev Davidescus',
            'email'  => 'test@test.com',
            'password'       => sha1('admin'),
        ]);
    }
}
