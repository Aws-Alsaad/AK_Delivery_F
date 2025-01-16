<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreEmail extends Model
{
    use HasFactory;

    protected $fillable=[
        'store_id',
        'type',
        'link',
    ];

    public function store(){
        return $this->belongsTo(Store::class);
    }
}
