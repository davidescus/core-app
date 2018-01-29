<?php namespace App\Models\AutoUnit;

use Illuminate\Database\Eloquent\Model;

class MountlySetting extends Model {

    protected $table = 'auto_unit_mountly_setting';

    protected $fillable = [
        'siteId',
        'date',
        'tipIdentifier',
        'minOdd',
        'maxOdd',
        'win',
        'loss',
        'draw',
        'winrate',
    ];

//    protected $hidden = [ ‘password’ ];
}


