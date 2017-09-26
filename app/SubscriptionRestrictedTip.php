<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionRestrictedTip extends Model {

    protected $table = 'subscription_restricted_tip';

    protected $fillable = [
        'subscriptionId',
        'distributionId',
        'systemDate',
    ];
}

