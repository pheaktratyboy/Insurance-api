<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'duration',
        'created_at',
        'updated_at',
        'logo',
        'disabled',
    ];

    protected $casts = [
        'logo'      => 'array',
        'disabled'  => 'boolean',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function notAllowIfItemAlreadyUsed()
    {
        $exist = SubscriberPolicy::firstWhere('policy_id', $this->id);
        if ($exist) {
            abort('422', 'Sorry, we can not allow doing the action because this item has already been used.');
        }
        return $this;
    }
}
