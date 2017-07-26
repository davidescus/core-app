<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Association extends Model {

    protected $table = 'association';

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
