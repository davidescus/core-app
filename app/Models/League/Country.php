<?php namespace App\Models\League;

use Illuminate\Database\Eloquent\Model;

class Country extends Model {

    protected $table = 'league_country';

    protected $fillable = [
        'countryCode',
        'leagueId',
    ];

//    protected $hidden = [ ‘password’ ];
}


