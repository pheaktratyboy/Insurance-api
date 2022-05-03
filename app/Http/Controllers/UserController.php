<?php

namespace App\Http\Controllers;

use App\Enums\BaseRole;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\UserAllResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{

    public function getAllUser() {

        $user = auth()->user();
        $users = User::where('created_by', $user->id)->where('disabled', false)->with('profile')->role(BaseRole::Subscriber)->get();
        return UserAllResource::collection($users);
    }

    public function index() {

        $users = QueryBuilder::for(User::role(BaseRole::Admin))
            ->allowedFilters(['username'])
            ->where('disabled', 0)
            ->defaultSort('-created_at')
            ->paginate()
            ->appends(request()->query());

        return UserResource::collection($users);
    }

    public function forceChangePassword(Request $request, User $user)
    {
        $request->validate([
            'password'         => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ]);

        if ($user->hasRole(BaseRole::Admin) || $user->hasRole(BaseRole::Master)) {
            abort('422', 'this user can not update.');
        }

        return DB::transaction(function () use ($request, $user) {

            $user->password = bcrypt($request->password);
            $user->save();

            /** revoke all old token of current user */
            $user->tokens->each->revoke();
        });
    }

    /**
     * @param User $user
     * @return UserResource
     */
    public function show(User $user)
    {
        if (!$user->hasRole(BaseRole::Master) || !$user->hasRole(BaseRole::Admin)) {
            abort('422', 'Sorry, you can not view this data.');
        }
        return new UserResource($user->load(['profile', 'profile.municipality', 'profile.district', 'roles']));
    }

    /**
     * @return EmployeeResource
     */
    public function showProfile() {

        $user = Auth::user();
        return new EmployeeResource($user->profile->load(['user.roles']));
    }

    /**
     * @param UpdateUserRequest $request
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function update(UpdateUserRequest $request, User $user) {

        DB::transaction(function () use ($request, $user) {

            if ($request->has('email')) {
                $user->username = $request->input('email');
            }
            $user->update($request->validated());
        });
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
