<?php namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;

class Log extends Model {

    protected $table = 'log';

    protected $fillable = [
        'type',
        'identifier',
        'status',
        'info',
    ];

//    protected $hidden = [ ‘password’ ];
}




