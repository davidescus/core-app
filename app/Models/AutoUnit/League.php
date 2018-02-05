<?php namespace App\Models\AutoUnit;

use Illuminate\Database\Eloquent\Model;

class League extends Model {

    protected $table = 'auto_unit_league';

    protected $fillable = [
        'siteId',
        'tipIdentifier',
        'leagueId',
    ];

//    protected $hidden = [ ‘password’ ];
}
