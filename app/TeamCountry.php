<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamCountry extends Model {

    protected $table = 'team_country';

    protected $fillable = [
        'countryCode',
        'teamId',
    ];

//    protected $hidden = [ ‘password’ ];
}
