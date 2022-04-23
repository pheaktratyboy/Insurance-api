<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'email'                 => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users')->ignore(request()->route('user')->id)],
            'force_change_password' => 'sometimes|required|boolean',
            'disabled'              => 'sometimes|required|boolean',
            'activated'             => 'sometimes|required|boolean',
        ];
    }
}
