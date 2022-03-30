<?php

namespace App\Http\Controllers;

use App\Enums\BaseRole;
use App\Http\Requests\CreateAgencyRequest;
use App\Http\Requests\UpdateAgencyRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Agency;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AgencyController extends Controller
{
    public function index() {

    }

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

    public function update(UpdateAgencyRequest $request, Agency $agency) {

    }
}
