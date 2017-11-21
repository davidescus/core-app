<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Cron extends Model {
    protected $table = 'cron';

    protected $fillable = [
        'type',
        'date_start',
        'date_end',
        'info',
    ];
    public $timestamps = false;
}