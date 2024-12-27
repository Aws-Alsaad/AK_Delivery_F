<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable=[
        'lat',
        'lon',
        'display_name',
        'state',
        'city',
        'town',
        'suburb',
        'road',
    ];
}
