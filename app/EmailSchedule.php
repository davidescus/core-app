<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailSchedule extends Model {

    protected $table = 'email_schedule';

    protected $fillable = [
        'provider',
        'sender',
        'type',
        'identifierName',
        'identifierValue',
        'from',
        'fromName',
        'to',
        'toName',
        'subject',
        'body',
        'mailingDate',
        'status',
        'info',
    ];

//    protected $hidden = [ ‘password’ ];
}

