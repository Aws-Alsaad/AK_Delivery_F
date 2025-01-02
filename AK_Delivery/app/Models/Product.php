<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable=[
        'store_id',
        'name',
        'price',
        'quantity',
        'product_date',
        'end_date',
        'product_image_path',
    ];

    public function store(){
        return $this->belongsTo(Store::class);
    }
}
