<?php namespace App\Models\AutoUnit;

use Illuminate\Database\Eloquent\Model;

class DailySchedule extends Model {

    protected $table = 'auto_unit_daily_schedule';

    protected $fillable = [
        'siteId',
        'tipIdentifier',
        'tableIdentifier',
        'predictionGroup',
        'statusId',
        'systemDate',
    ];

//    protected $hidden = [ ‘password’ ];
}


