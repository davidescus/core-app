<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Distribution extends Model {

    protected $table = 'distribution';

    protected $fillable = [
        'eventId',
        'source',
        'provider',
        'type',
        'isNotip',
        'isVip',
        'country',
        'league',
        'homeTeam',
        'awayTeam',
        'odd',
        'predictionId',
        'result',
        'statusId',
        'eventDate',
        'systemDate',
    ];

//    protected $hidden = [ ‘password’ ];
}
