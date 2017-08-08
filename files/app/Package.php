<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model {

    protected $table = 'package';

    protected $fillable = [
        'siteId',
        'name',
        'identifier',
        'tipIdentifier',
        'tableIdentifier',
        'paymentName',
        'vipFlag',
        'isVip',
        'isRecurring',
        'subscriptionType',
        'tipsPerDay',
        'subscription',
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
