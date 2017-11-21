<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ArchiveHome extends Model {

    protected $table = 'archive_home';

    protected $fillable = [
        'distributionId',
        'order',
        'associationId',
        'eventId',
        'siteId',
        'packageId',
        'source',
        'provider',
        'tableIdentifier',
        'tipIdentifier',
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
        'stringEventDate',
        'eventDate',
        'mailingDate',
        'publishDate',
        'systemDate',
    ];

//    protected $hidden = [ ‘password’ ];
}

