<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model {

    protected $table = 'package';

    protected $fillable = [
        'siteId',
        'name',
        'tipIdentifier',
        'tableIdentifier',
        'isVip',
    ];

//    protected $hidden = [ ‘password’ ];
}
