<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function index() {
        $users = QueryBuilder::for(User::class)
            ->allowedFilters(['username'])
            ->defaultSort('-created_at')
            ->paginate()
            ->appends(request()->query());

        return UserResource::collection($users);
    }

    /**
     * @param User $user
     * @return UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user->load(['profile', 'profile.municipality', 'profile.district', 'roles']));
    }

    /**
     * @return EmployeeResource
     */
    public function showProfile() {

        $user = Auth::user();
        return new EmployeeResource($user->profile->load('user', 'user.roles'));
    }

    /**
     * @param UpdateUserRequest $request
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function update(UpdateUserRequest $request, User $user) {

        DB::transaction(function () use ($request, $user) {
            $user->update($request->validated());
        });
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
