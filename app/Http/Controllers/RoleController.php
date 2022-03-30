<?php

namespace App\Http\Controllers;

use App\Enums\BaseRole;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Spatie\QueryBuilder\QueryBuilder;

class RoleController extends Controller
{
    public function listAll()
    {
        $roles = Role::whereNotIn('name', [BaseRole::Master])
            ->where('base', true)
            ->get();

        return RoleResource::collection($roles);
    }

    public function index()
    {
        $roles = QueryBuilder::for(Role::class)
            ->allowedFilters(['name'])
            ->paginate()
            ->appends(request()->query());

        return RoleResource::collection($roles);
    }

    /**
     * @param Role $role
     * @return RoleResource
     */
    public function show(Role $role)
    {
        return new RoleResource($role);
    }
}
