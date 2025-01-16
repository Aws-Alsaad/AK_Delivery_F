<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'id',
        'type',
        'notifiable',
        'data',
        'read',
        'read_at',
    ];
}
