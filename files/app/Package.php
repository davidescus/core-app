<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model {

    protected $table = 'package';

    protected $fillable = [
        'siteId',
        'name',
        'tipIdentifier',
        'tableIdentifier',
        'paymentName',
        'isVip',
        'isRecurring',
        'subscriptionType',
        'tipsPerDay',
        'tipsTotal',
        'aliasTipsPerDay',
        'aliasTipsTotal',
        'oldPrice',
        'discount',
        'price',
        'email',
        'fromName',
        'subject',
    ];

//    protected $hidden = [ ‘password’ ];
}
