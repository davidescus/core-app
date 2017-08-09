<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteResultStatus extends Model {

    public $timestamps = false;

    protected $table = 'site_result_status';

    protected $fillable = [
        'siteId',
        'statusId',
        'statusName',
        'statusClass',
    ];

//    protected $hidden = [ ‘password’ ];
}
