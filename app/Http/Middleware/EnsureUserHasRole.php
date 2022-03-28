<?php

namespace App\Http\Middleware;

use App\Exceptions\HttpException;
use Closure;
use Illuminate\Http\Request;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (! $request->user()->hasRole($roles)) {
            throw new HttpException(403, "You don't have permission to perform current operations.");
        }
        return $next($request);
    }
}
