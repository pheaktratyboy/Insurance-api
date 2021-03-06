<?php

namespace App\Http\Controllers;

use App\Enums\BaseRole;
use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\UserResource;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;


class EmployeeController extends Controller
{
    public function getAllStaff()
    {
        $users = User::with('profile')->role(BaseRole::Staff)->get();
        return UserResource::collection($users);
    }

    public function index()
    {
        $queryBuilder = QueryBuilder::for( User::role(BaseRole::Staff))
            ->allowedFilters(['full_name'])
            ->defaultSort('-created_at')
            ->paginate()
            ->appends(request()->query());

        return UserResource::collection($queryBuilder);
    }

    /**
     * @param CreateEmployeeRequest $request
     * @return EmployeeResource
     */
    public function store(CreateEmployeeRequest $request) {

        $employee = DB::transaction(function () use ($request) {

            $employee = new Employee($request->input());
            $employee->save();

            if ($request->has('email')) {

                /**@var User $user*/
                $user = $employee->user()->create([
                    'username'              => $request->email,
                    'email'                 => $request->email,
                    'full_name'             => $employee->name_en,
                    'phone_number'          => $employee->phone_number,
                    'password'              => bcrypt($request->password),
                    'force_change_password' => false,
                    'activated'             => false,
                    'disabled'              => false,
                ]);

                /** Assign Role */
                if ($request->has('role_id')) {
                    $role = Role::firstWhere('id', $request->role_id);
                    $user->assignRole($role->name);

                } else {
                    /** Assign Staff Role */
                    $user->assignRole(BaseRole::Staff);
                }
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

            if ($request->has('role_id')) {

                $role = Role::firstWhere('id', $request->role_id);
                $employee->user()->assignRole($role->name);
            }

            $employee->user()->first()
                ->updateFullName($request->only('name_en'))
                ->activateUser($request->only('activated'))
                ->updateEnableOrDisabled($request->only('disabled'));

            $employee->update($request->input());
        });

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Employee $employee
     * @return EmployeeResource
     */
    public function show(Employee $employee) {
        return new EmployeeResource($employee->load('user', 'municipality', 'district'));
    }
}
