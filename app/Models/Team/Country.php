<?php namespace App\Models\Team;

use Illuminate\Database\Eloquent\Model;

class Country extends Model {

    protected $table = 'team_country';

    protected $fillable = [
        'countryCode',
        'teamId',
    ];

//    protected $hidden = [ ‘password’ ];
}

