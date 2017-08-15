<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model {

    protected $table = 'match';

    protected $fillable = [
        'country',
        'countryCode',
        'league',
        'homeTeam',
        'homeTeamId',
        'awayTeam',
        'awayTeamId',
        'result',
        'eventDate',
    ];

//    protected $hidden = [ ‘password’ ];
}
