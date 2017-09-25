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
        'tipIdentifier',
        'isPublish',
        'isPublishInSite',
        'isVisible',
        'isNoTip',
        'isVip',
        'country',
        'countryCode',
        'league',
        'leagueId',
        'homeTeam',
        'homeTeamId',
        'awayTeam',
        'awayTeamId',
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
