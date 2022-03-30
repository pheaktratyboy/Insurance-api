<?php

namespace App\Http\Controllers;

use App\Enums\BaseRole;
use App\Exceptions\UserException;
use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;


class EmployeeController extends Controller
{

    public function getAllEmployee()
    {
        $role = Role::where('name', BaseRole::Staff)->first();
        $user = $role->users()->with('profile')->get();

        return EmployeeResource::collection($user);
    }

    public function getAllAgency()
    {
        $role = Role::where('name', BaseRole::Agency)->first();
        $user = $role->users()->with('profile')->get();

        return EmployeeResource::collection($user);
    }

    /**
     * @param CreateEmployeeRequest $request
     * @return EmployeeResource
     */
    public function store(CreateEmployeeRequest $request) {

        $employee = DB::transaction(function () use ($request) {

            $employee = new Employee($request->input());
            $employee->save();

            if ($request->has('username') && $request->has('email')) {

                /**@var User $user*/
                $user = $employee->user()->create([
                    'username'              => $request->email,
                    'email'                 => $request->email,
                    'full_name'             => $request->username,
                    'password'              => bcrypt($request->password),
                    'phone_number'          => $employee->phone_number,
                    'force_change_password' => false,
                    'activated'             => false,
                    'disabled'              => false,
                ]);

                /** Assign Staff Role */
                $user->assignRole(BaseRole::Staff);
            }

            return $employee;
        });

        return new EmployeeResource($employee);
    }

    /**
     * @param UpdateEmployeeRequest $request
     * @param Employee $employee
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee) {

        DB::transaction(function () use ($request, $employee) {
            $employee->update($request->input());
        });

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Employee $employee
     * @return EmployeeResource
     */
    public function show(Employee $employee) {
        return new EmployeeResource($employee);
    }
}
