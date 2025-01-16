<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SuperClient extends Authenticatable
{
    use HasFactory,HasApiTokens,Notifiable;

    protected $fillable=[
        'store_id',
        'name',
        'email',
        'password',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function store(){
        return $this->belongsTo(Store::class);
    }
    public function superClientCS(){
        return $this->hasMany(SuperClientCS::class);
    }
}
