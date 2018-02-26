<?php namespace App\Models\Team;

use Illuminate\Database\Eloquent\Model;

class Alias extends Model {

    protected $table = 'team_alias';

    protected $fillable = [
        'teamId',
        'alias',
    ];

//    protected $hidden = [ ‘password’ ];
}


