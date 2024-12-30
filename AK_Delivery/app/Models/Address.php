<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

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

    public function store(){
        return $this->hasOne(Store::class);
    }
}
