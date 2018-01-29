<?php namespace App\Models\AutoUnit;

use Illuminate\Database\Eloquent\Model;

class MonthlySetting extends Model {

    protected $table = 'auto_unit_monthly_setting';

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


