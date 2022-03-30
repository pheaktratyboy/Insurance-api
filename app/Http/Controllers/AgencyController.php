<?php

namespace App\Http\Controllers;

use App\Enums\BaseRole;
use App\Http\Requests\CreateAgencyRequest;
use App\Http\Requests\UpdateAgencyRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class AgencyController extends Controller
{

    public function index() {

        $districts = QueryBuilder::for(Employee::class)
            ->allowedFilters(['name'])
            ->paginate()
            ->appends(request()->query());

        return EmployeeResource::collection($districts);
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
}
