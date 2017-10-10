<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class AppResultStatus extends Model {

    protected $table = 'app_result_status';

    protected $fillable = [
        'statusName',
    ];

//    protected $hidden = [ ‘password’ ];
}
