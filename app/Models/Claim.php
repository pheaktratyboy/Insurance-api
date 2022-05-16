<?php

namespace App\Models;

use App\Traits\Blamable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory, Blamable;

    protected $fillable = [
        'user_id',
        'subscriber_id',
        'note',
        'status',
        'attachments',
    ];

    protected $casts = [
        'user_id'           => 'integer',
        'subscriber_id'     => 'integer',
        'attachments'       => 'array',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

}
