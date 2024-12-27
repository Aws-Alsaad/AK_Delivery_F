<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable=[
        'address_id',
        'name',
        'profile_photo_path',
    ];
}
