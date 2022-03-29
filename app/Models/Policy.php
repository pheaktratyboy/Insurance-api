<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'price', 'duration', 'created_at', 'updated_at',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
