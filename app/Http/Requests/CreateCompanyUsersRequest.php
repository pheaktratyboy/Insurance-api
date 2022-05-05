<?php

namespace App\Http\Requests;

use App\Enums\BaseRole;
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
            'type'              => ['required', ['required', Rule::in(BaseRole::getValues())],],
            'users.*.user_id'   => ['required', 'numeric', 'max:10', Rule::exists('users', 'id')],
        ];
    }
}
