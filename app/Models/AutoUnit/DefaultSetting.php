<?php namespace App\Models\AutoUnit;

use Illuminate\Database\Eloquent\Model;

class DefaultSetting extends Model {

    protected $table = 'auto_unit_default_setting';

    protected $fillable = [
        'siteId',
    ];

//    protected $hidden = [ ‘password’ ];
}

