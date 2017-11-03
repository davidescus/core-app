<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ArchiveHomeConf extends Model {

    protected $table = 'archive_home_conf';

    protected $fillable = [
        'siteId',
        'tableIdentifier',
        'dateStart',
        'eventsNumber',
    ];

//    protected $hidden = [ ‘password’ ];
}


