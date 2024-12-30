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
        'road',
    ];

    public function client(){
        return $this->hasOne(Client::class);
    }
}
