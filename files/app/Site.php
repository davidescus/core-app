<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model {

    protected $table = 'site';

    protected $fillable = [
        'name',
    ];

//    protected $hidden = [ ‘password’ ];
}
