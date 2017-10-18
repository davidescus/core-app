<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageSection extends Model {

    protected $table = 'package_section';

    protected $fillable = [
        'packageId',
        'section',
        'systemDate',
    ];

//    protected $hidden = [ ‘password’ ];
}

