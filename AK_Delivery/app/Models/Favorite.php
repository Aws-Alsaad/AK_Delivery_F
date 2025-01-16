<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable=[
        'client_id',
        'product_id',
        'store_id',
    ];

    public function client(){
        return $this->belongsToMany(Client::class);
    }
    public function product(){
        return $this->belongsToMany(Product::class);
    }
    public function store(){
        return $this->belongsToMany(Store::class);
    }
}
