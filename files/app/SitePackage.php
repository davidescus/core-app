<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SitePackage extends Model {

    public $timestamps = false;

    protected $table = 'site_package';

    protected $fillable = [
        'siteId',
        'packageId',
    ];

//    protected $hidden = [ ‘password’ ];
}
