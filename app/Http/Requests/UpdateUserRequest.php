<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username'              => ['sometimes', 'required', 'string', 'max:255'],
            'email'                 => ['sometimes', 'required', 'email', 'unique:users', 'max:255'],
            'force_change_password' => 'sometimes|required|boolean',
            'disabled'              => 'sometimes|required|boolean',
            'activated'             => 'sometimes|required|boolean',
        ];
    }
}
