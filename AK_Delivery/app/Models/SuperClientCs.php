<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuperClientCs extends Model
{
    use HasFactory;
    protected $fillable=[
        'super_client_id',
        'text',
    ];

    public function superClient(){
        return $this->belongsTo(SuperClient::class);
    }
}
