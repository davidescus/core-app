<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Distribution extends Model {

    protected $table = 'distribution';

    protected $fillable = [
        'associationId',
        'eventId',
        'siteId',
        'packageId',
        'source',
        'provider',
        'isPublish',
        'isNotip',
        'isVip',
        'country',
        'league',
        'homeTeam',
        'awayTeam',
        'odd',
        'predictionId',
        'predictionName',
        'result',
        'statusId',
        'eventDate',
        'systemDate',
        'mailingDate',
    ];

//    protected $hidden = [ ‘password’ ];
}
