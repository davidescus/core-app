<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model {

    protected $table = 'subscription';

    protected $fillable = [
        'id',
        'name',
        'customerId',
        'siteId',
        'packageId',
        'isCustom',
        'type',
        'subscription',
        'dateStart',
        'dateEnd',
        'tipsLeft',
        'tipsBlocked',
        'status',
        'archivedDate',
    ];
}
