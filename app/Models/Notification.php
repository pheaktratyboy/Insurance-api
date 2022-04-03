<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'message',
        'is_read'
    ];

    protected $casts  = [
        'is_read' => 'boolean',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
