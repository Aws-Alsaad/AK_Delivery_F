<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable=[
        'store_id',
        'name',
        'price',
        'quantity',
        'product_date',
        'end_date',
        'product_image_path',
    ];
}
