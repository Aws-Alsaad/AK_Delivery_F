<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientCs extends Model
{
    use HasFactory;
    protected $fillable=[
        'client_id',
        'text',
    ];

    public function client(){
        return $this->belongsTo(Client::class);
    }
}
