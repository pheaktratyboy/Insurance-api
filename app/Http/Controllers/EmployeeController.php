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
use Illuminate\Support\Str;
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
        $queryBuilder = QueryBuilder::for(User::role(BaseRole::Staff))
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

                $param = [
                    'username'              => $request->email,
                    'email'                 => $request->email,
                    'full_name'             => $employee->name_en,
                    'phone_number'          => $employee->phone_number,
                    'password'              => bcrypt($request->password),
                    'force_change_password' => false,
                    'activated'             => $request->activated ?: false,
                    'disabled'              => $request->disabled ?: false,
                    'remember_token'        => Str::random(10),
                ];

                if ($request->has('activated') && $request->activated == true) {
                    $param["activated_at"] = now();
                }

                /**@var User $user*/
                $user = $employee->user()->create($param);

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
                ->isUpdateIfHasName($request->only('name_en'))
                ->updateIsNotActivate($request->only('activated'))
                ->isUpdateEnableOrDisabled($request->only('disabled'));

            $employee->update($request->input());
        });

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param User $user
     * @return EmployeeResource
     */
    public function show(User $user) {

        if ($user->hasRole([BaseRole::Master, BaseRole::Admin, BaseRole::Subscriber])) {
            abort('422', 'Sorry, you can not view this data.');
        }

        return new EmployeeResource($user->profile->load('municipality', 'district'));
    }
}
