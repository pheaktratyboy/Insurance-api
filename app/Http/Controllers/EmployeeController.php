<?php

namespace App\Http\Controllers;

use App\Enums\BaseRole;
use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Role;


class EmployeeController extends Controller
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getAllStaff()
    {
        $role = Role::where('name', BaseRole::Staff)->first();
        $user = $role->users()->with('profile')->get();

        return EmployeeResource::collection($user);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getAllAgency()
    {
        $role = Role::where('name', BaseRole::Agency)->first();
        $user = $role->users()->with('profile')->get();

        return EmployeeResource::collection($user);
    }

    public function store(CreateEmployeeRequest $request) {

    }

    public function update() {

    }
}
