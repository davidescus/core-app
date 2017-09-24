<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailSchedule extends Model {

    protected $table = 'email_schedule';

    protected $fillable = [
        'provider',
        'type',
        'identifierName',
        'identifierValue',
        'host',
        'user',
        'pass',
        'port',
        'encryption',
        'from',
        'fromName',
        'to',
        'toName',
        'subject',
        'body',
        'mailingDate',
        'status',
    ];

//    protected $hidden = [ ‘password’ ];
}

