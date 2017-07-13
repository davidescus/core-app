<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Event;

class EventTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            ‘name’ => ‘Pete Houston’,
            ‘username’ => ‘petehouston’,
            ‘password’ => ‘123secret’
        ]);
    }
}
