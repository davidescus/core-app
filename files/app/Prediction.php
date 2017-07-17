<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Prediction extends Model {

    protected $table = 'prediction';

    protected $fillable = [
        'identifier',
        'name',
        'group'
    ];

//    protected $hidden = [ ‘password’ ];
}
