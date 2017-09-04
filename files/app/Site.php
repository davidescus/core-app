<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model {

    protected $table = 'site';

    protected $fillable = [
        'name',
        'email',
        'smtpHost',
        'smtpPort',
        'smtpUser',
        'smtpPassword',
        'smtpEncryption',
        'imapHost',
        'imapPort',
        'imapUser',
        'imapPassword',
        'imapEncryption',
        'dateFormat',
        'isConnect',
        'token',
    ];

//    protected $hidden = [ ‘password’ ];
}
