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

        // Add admins to aplication.
        User::firstOrCreate([
            'name'       => 'David D',
            'email'      => 'david@app.com',
            'password'   => sha1('admin'),
        ]);
        User::firstOrCreate([
            'name'       => 'Florin H',
            'email'      => 'florin@app.com',
            'password'   => sha1('admin'),
        ]);
        User::firstOrCreate([
            'name'       => 'Cristi B',
            'email'      => 'cristi@app.com',
            'password'   => sha1('admin'),
        ]);
    }
}
