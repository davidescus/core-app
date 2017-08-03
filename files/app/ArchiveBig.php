<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ArchiveBig extends Model {

    protected $table = 'archive_big';

    protected $fillable = [
        'distributionId',
        'associationId',
        'eventId',
        'siteId',
        'packageId',
        'source',
        'provider',
        'tableIdentifier',
        'isPublish',
        'isVisible',
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
        'mailingDate',
        'systemDate',
    ];

//    protected $hidden = [ ‘password’ ];
}
