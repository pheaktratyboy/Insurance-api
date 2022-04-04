<?php

namespace App\Models;

use App\Traits\Blamable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory, Blamable;


    protected $fillable = [
        'name',
        'message',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
