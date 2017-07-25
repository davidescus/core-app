<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Association extends Model {

    protected $table = 'event';

    protected $fillable = [
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
