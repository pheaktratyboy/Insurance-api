<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterStaffRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username'  => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', Rule::exists('users', 'email')],
            'password'  => ['required', 'string', 'min:6']
        ];
    }
}
