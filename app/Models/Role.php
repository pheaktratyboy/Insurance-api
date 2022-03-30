<?php

namespace App\Models;

use App\Traits\Blamable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory, Blamable;

    protected $guard_name = 'api';

    protected $fillable = [
        'label',
        'name',
        'description',
        'guard_name',
        'base'
    ];

    protected $casts = [
        'base' => 'boolean',
    ];

    public function scopeGetBase($query, $role)
    {
        return $query
            ->where('base', true)
            ->where('name', $role)->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
