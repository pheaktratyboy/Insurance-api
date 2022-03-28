<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ConvertValidPhoneNumberToE164Format
{
    public function handle(Request $request, Closure $next, ...$fields)
    {
        $parameterBag = $request->input();
        foreach ($fields as $field) {
            $phone = data_get($parameterBag, $field);
            if (!$phone) {
                continue;
            }
            if (is_array($phone)) {
                $phone = data_get($phone, 'country_code') . data_get($phone, 'number');
            }
            $phone = (string) phone($phone, 'KH');
            data_set($parameterBag, $field, $phone);
        }
        $request->merge($parameterBag);

        return $next($request);
    }
}
