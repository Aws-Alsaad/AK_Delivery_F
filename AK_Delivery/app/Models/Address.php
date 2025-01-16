<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    protected $fillable=[
        'state',
        'city',
        'town',
        'area',
        'street',
        'notes',
        'display_name',
    ];

    public function client(){
        return $this->hasOne(Client::class);
    }

    public function store(){
        return $this->hasOne(Store::class);
    }
    public function orders(){
        return $this->hasOne(Order::class);
    }
}
