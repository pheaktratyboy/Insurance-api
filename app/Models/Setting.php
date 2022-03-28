<?php

namespace App\Models;

use App\Traits\Blamable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory, Blamable;

    protected $fillable = ['name','option'];
    protected $casts    = ['option' => 'array'];
}
