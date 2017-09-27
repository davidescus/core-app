<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class PackagePrediction extends Model {

    public $timestamps = false;
    protected $table = 'package_prediction';

    protected $fillable = [
        'packageId',
        'predictionIdentifier',
    ];

//    protected $hidden = [ ‘password’ ];
}
