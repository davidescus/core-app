<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerRestrictedTip extends Model {

    protected $table = 'customer_restricted_tip';

    protected $fillable = [
        'customerId',
        'packageId',
        'distributionId',
        'systemDate',
    ];
}

