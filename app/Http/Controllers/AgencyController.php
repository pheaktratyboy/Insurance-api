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
use Illuminate\Support\Str;
use Spatie\QueryBuilder\QueryBuilder;

class AgencyController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $agency = User::role(BaseRole::Agency);
        if ($user->hasRole([BaseRole::Staff, BaseRole::Agency])) {
            $queryBuilder = QueryBuilder::for($agency)
                ->where('created_by', $user->id)
                ->allowedFilters([['full_name', 'activated', 'disabled', 'email']])
                ->defaultSort('-created_at')
                ->paginate()
                ->appends(request()->query());
        } else {
            $queryBuilder = QueryBuilder::for($agency)
                ->allowedFilters([['full_name', 'activated', 'disabled', 'email']])
                ->defaultSort('-created_at')
                ->paginate()
                ->appends(request()->query());
        }

        return UserResource::collection($queryBuilder);
    }

    /**
     * @param CreateAgencyRequest $request
     * @return UserResource
     */
    public function store(CreateAgencyRequest $request)
    {
        $agency = DB::transaction(function () use ($request) {
            $employee = new Employee($request->input());
            $employee->save();

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
                'remember_token'        => Str::random(10),
            ]);

            /** Assign Agency Role */
            $user->assignRole(BaseRole::Agency);

            return $user;
        });

        return new UserResource($agency);
    }

    /**
     * @param UpdateAgencyRequest $request
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function update(UpdateAgencyRequest $request, User $user)
    {
        DB::transaction(function () use ($request, $user) {

            $user->isUpdateIfHasName($request->only('name_en'))
                ->isUpdateIfHasEmail($request->only('email'))
                ->isUpdateEnableOrDisabled($request->only('disabled'));

            $user->profile->update($request->input());
        });

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param User $user
     * @return EmployeeResource
     */
    public function show(User $user)
    {
        if ($user->hasRole([BaseRole::Master, BaseRole::Admin, BaseRole::Subscriber, BaseRole::Staff])) {
            abort('422', 'Sorry, you can not view this data.');
        }

        return new EmployeeResource($user->profile->load('user', 'municipality', 'district'));
    }
}
