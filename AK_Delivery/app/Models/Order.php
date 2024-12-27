<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable=[
        'client_id',
        'address_id',
        'phone_number',
        'products_price',
        'total_price',
    ];
}
