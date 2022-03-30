<?php

namespace App\Http\Controllers;

use App\Enums\BaseRole;
use App\Http\Requests\CreateAgencyRequest;
use App\Http\Requests\UpdateAgencyRequest;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\UserResource;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class AgencyController extends Controller
{

    public function index() {

        $user = auth()->user();
        $agency = User::role(BaseRole::Agency);
        $queryBuilder = QueryBuilder::for($agency)
            ->where('created_by', $user->id)
            ->allowedFilters(['full_name'])
            ->defaultSort('-created_at')
            ->paginate()
            ->appends(request()->query());

        return UserResource::collection($queryBuilder);
    }

    /**
     * @param CreateAgencyRequest $request
     * @return EmployeeResource
     */
    public function store(CreateAgencyRequest $request) {

        $agency = DB::transaction(function () use ($request) {

            $user = auth()->user();
            $employee = new Employee($request->input());
            $employee->user_id = $user->id;
            $employee->save();

            if ($request->has('username') && $request->has('email')) {

                /**@var User $user*/
                $user = $employee->user()->create([
                    'username'              => $request->email,
                    'email'                 => $request->email,
                    'full_name'             => $request->username,
                    'password'              => bcrypt($request->password),
                    'phone_number'          => $employee->primary_phone,
                    'force_change_password' => $employee->force_change_password,
                    'activated'             => true,
                    'activated_at'          => now(),
                    'disabled'              => false,
                ]);

                /** Assign Staff Role */
                $user->assignRole(BaseRole::Agency);
            }

            return $employee;
        });

        return new EmployeeResource($agency);
    }

    /**
     * @param UpdateAgencyRequest $request
     * @param Employee $agency
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function update(UpdateAgencyRequest $request, Employee $agency) {
        DB::transaction(function () use ($request, $agency) {
            $agency->update($request->input());
        });

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Employee $employee
     * @return EmployeeResource
     */
    public function show(Employee $employee) {
        return new EmployeeResource($employee->load('user'));
    }
}
