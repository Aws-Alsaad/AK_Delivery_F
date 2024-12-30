<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Client extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $fillable = [
        'name',
        'phone_number',
        'password',
        'email',
        'profile_photo_path',
        'address_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function address(){
        return $this->belongsTo(Address::class);
    }
}
