<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterStaffRequest;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\UserResource;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function index() {
        $users = QueryBuilder::for(User::class)
            ->allowedFilters(['username'])
            ->paginate()
            ->appends(request()->query());

        return UserResource::collection($users);
    }

    public function showProfile()
    {
        /**@var User $user*/
        $user = Auth::user();

        return new EmployeeResource($user->profile->load('user', 'user.roles', 'municipality', 'district'));
    }

    /**
     * @param User $user
     * @return UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function registerStaff(RegisterStaffRequest $request) {

        $user = DB::transaction(function () use ($request) {
            /**@var Employee $employee*/

            /**@var User $user*/
            $user = $customer->user()->create([
                'username'          => $customer->primary_phone,
                'password'          => bcrypt(Str::random(6)),
                'phone_number'      => $customer->primary_phone,
                'email'             => $customer->primary_email,
                'email_verified_at' => now(),
            ]);

            /** update password */
            if ($request->has('password')) {
                $user->update(['password' => $request->password]);
            }

            /** assign role */
            $role = Role::firstWhere('id', $request->role_id);
            $user->assignRole($role->name);

            return $user;
        });

        return new UserResource($user);
    }
}
