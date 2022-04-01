<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCompanyUsersRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            /** Information */
            'users.*.user_id'   => ['required', 'numeric', 'max:10', Rule::exists('users', 'id')],
        ];
    }
}
