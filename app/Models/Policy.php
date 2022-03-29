<?php

namespace App\Models;

use App\Traits\Blamable;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use Blamable;

    protected $fillable = [
        'name', 'price', 'created_at', 'updated_at',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
