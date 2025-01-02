<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $fillable=[
        'order_id',
        'product_id',
        'store_name',
        'product_name',
        'quantity',
        'the_price',
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }
}
