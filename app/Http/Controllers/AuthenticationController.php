<?php

namespace App\Http\Controllers;

use App\Enums\BaseRole;
use App\Exceptions\AuthenticationFailedException;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\PassportAuthenticationTrait;
use Laravel\Passport\Http\Controllers\AccessTokenController;


class AuthenticationController extends AccessTokenController
{
    use PassportAuthenticationTrait;

    public function employeeLogin(LoginRequest $request)
    {
        /** @var User $user */
        $user = User::firstWhere('username', $request->username);
        if ($user) {
            if (!$user->activated) {
                throw AuthenticationFailedException::accountNotInActivated();
            }
            if ($user->disabled) {
                throw AuthenticationFailedException::accountDisabled();
            }
            if ($user->hasRole(BaseRole::Subscriber)) {
                throw AuthenticationFailedException::invalidCredential();
            }
        }

        return $this->login($request->username, $request->password);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password'     => ['required', new MatchOldPassword],
            'new_password'     => 'required|min:6',
            'confirm_password' => 'same:new_password',
        ]);

        return DB::transaction(function () {
            /** @var User $user */
            $user = Auth::user();
            $user->password = bcrypt(request('new_password'));
            $user->save();
            $user->tokens->each->revoke();

            return $this->login($user->username, request('new_password'));
        });
    }

    public function createPassword(Request $request)
    {
        $request->validate([
            'password'         => 'required|min:6',
            'confirm_password' => 'same:password',
        ]);

        return DB::transaction(function () use ($request) {
            /** @var User $user */
            $user = Auth::user();
            $user->password = bcrypt($request->password);
            $user->activated_at = now();
            $user->force_change_password = false;
            $user->save();

            /** revoke all old token of current user */
            $user->tokens->each->revoke();

            /** login to get token response */
            return $this->login($user->username, $request->password);
        });
    }

    public function logout()
    {
        return response(null, 204);
    }
}
