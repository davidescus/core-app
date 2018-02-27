<?php namespace App\Models\League;

use Illuminate\Database\Eloquent\Model;

class League extends Model {

    protected $table = 'league_country';

    protected $fillable = [
        'countryCode',
        'leagueId',
    ];

//    protected $hidden = [ ‘password’ ];
}


