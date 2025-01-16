<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model
{
    use HasFactory;

    protected $fillable=[
        'address_id',
        'name',
        'profile_photo_path',
    ];

    public function address(){
        return $this->belongsTo(Address::class);
    }
    public function products(){
        return $this->hasMany(Product::class);
    }
    public function phoneNumbers(){
        return $this->hasMany(PhoneNumber::class);
    }
    public function storeEmails(){
        return $this->hasMany(StoreEmail::class);
    }
    public function Favorites(){
        return $this->hasMany(Favorite::class);
    }

    public function superClient(){
        return $this->hasOne(SuperClient::class);
    }
}
