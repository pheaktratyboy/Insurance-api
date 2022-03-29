<?php

namespace App\Http\Controllers;

use App\Enums\BaseRole;
use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Models\Role;


class EmployeeController extends Controller
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getAllEmployee()
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

    public function update(UpdateEmployeeRequest $request, Employee $employee) {

    }
}
