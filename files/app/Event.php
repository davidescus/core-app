<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {

    protected $table = 'event';

    protected $fillable = [
        'source',
        'provider',
        'country',
        'league',
        'homeTeam',
        'awayTeam',
        'odd',
        'predictionId',
        'result',
        'statusId',
        'eventDate',
    ];

//    protected $hidden = [ ‘password’ ];
}
