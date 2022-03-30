<?php

namespace App\Traits;

class RoleTrait extends \Spatie\Permission\Models\Role
{
    protected $fillable = ['name', 'guard_name'];

    public function scopeExcludeMaster($query)
    {
        return $query->where('name', '<>', 'master');
    }
}
