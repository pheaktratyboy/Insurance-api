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

        if ($user->hasRole([BaseRole::Staff, BaseRole::Agency])) {

            $agency = User::role(BaseRole::Agency);
            $queryBuilder = QueryBuilder::for($agency)
                ->where('created_by', $user->id)
                ->allowedFilters(['full_name'])
                ->defaultSort('-created_at')
                ->paginate()
                ->appends(request()->query());

        } else {

            $agency = User::role(BaseRole::Agency);
            $queryBuilder = QueryBuilder::for($agency)
                ->allowedFilters(['full_name'])
                ->defaultSort('-created_at')
                ->paginate()
                ->appends(request()->query());

        }

        return UserResource::collection($queryBuilder);
    }

    /**
     * @param CreateAgencyRequest $request
     * @return EmployeeResource
     */
    public function store(CreateAgencyRequest $request) {

        $agency = DB::transaction(function () use ($request) {

            $employee = new Employee($request->input());
            $employee->save();

            if ($request->has('email')) {

                /**@var User $user*/
                $user = $employee->user()->create([
                    'username'              => $request->email,
                    'email'                 => $request->email,
                    'full_name'             => $request->name_en,
                    'phone_number'          => $employee->phone_number,
                    'force_change_password' => $request->force_change_password,
                    'password'              => bcrypt($request->password),
                    'activated'             => true,
                    'activated_at'          => now(),
                    'disabled'              => false,
                ]);

                /** Assign Agency Role */
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

            $agency->user()->first()
                ->isUpdateIfHasName($request->only('name_en'))
                ->isUpdateEnableOrDisabled($request->only('disabled'));

            $agency->update($request->input());
        });

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param User $employee
     * @return EmployeeResource
     */
    public function show(User $employee) {

        if (!$employee->hasRole(BaseRole::Agency)) {
            abort('422', 'Sorry, you can not view this data.');
        }

        return new EmployeeResource($employee->profile->load('municipality', 'district'));
    }
}
