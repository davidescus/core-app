<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class League extends Model {

    public $timestamps = false;

    protected $table = 'league';

    protected $fillable = [
        'identifier',
        'name',
    ];

//    protected $hidden = [ ‘password’ ];
}
