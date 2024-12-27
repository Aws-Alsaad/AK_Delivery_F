<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneNumber extends Model
{
    protected $fillable=[
        'store_id',
        'phone_number',
    ];
}
