<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PhoneNumber extends Model
{
    use HasFactory;

    protected $fillable=[
        'store_id',
        'phone_number',
    ];

    public function store(){
        return $this->belongsTo(Store::class);
    }
}
