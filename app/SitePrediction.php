<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SitePrediction extends Model {

    public $timestamps = false;

    protected $table = 'site_prediction';

    protected $fillable = [
        'siteId',
        'predictionIdentifier',
        'name'
    ];

//    protected $hidden = [ ‘password’ ];
}
