<?php

namespace App\Http\Middleware;

use App\Exceptions\AuthorizationException;
use App\Models\Employee;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupLandlord
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /**@var User $user*/
        $user = Auth::user();

        if ($user->isCustomer()) {
            throw AuthorizationException::notBelongsToLandlordGroup();
        }

        /**@var Employee $employee*/
        $employee = $user->profile;
        if (!$employee || !$employee->isGroupOfLandlord()) {
            throw AuthorizationException::notBelongsToLandlordGroup();
        }

        return $next($request);
    }
}
