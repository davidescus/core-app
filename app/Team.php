<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model {

    public $timestamps = false;

    protected $table = 'team';

    protected $fillable = [
        'id',
        'name',
    ];

//    protected $hidden = [ ‘password’ ];
}
