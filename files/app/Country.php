<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SitePackage extends Model {

    public $timestamps = false;

    protected $table = 'country';

    protected $fillable = [
        'code',
        'name',
    ];

//    protected $hidden = [ ‘password’ ];
}
