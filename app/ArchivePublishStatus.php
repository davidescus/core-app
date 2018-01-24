<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ArchivePublishStatus extends Model {

    protected $table = 'archive_publish_status';

    protected $fillable = [
        'siteId',
        'type',
    ];

//    protected $hidden = [ ‘password’ ];
}



