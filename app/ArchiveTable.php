<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ArchiveTable extends Model {

    protected $table = 'archive_table';

    protected $fillable = [
        'siteId',
        'tableIdentifier',
        'dateStart',
        'eventsNumber',
    ];

//    protected $hidden = [ ‘password’ ];
}


