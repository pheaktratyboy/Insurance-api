<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyUsersRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id'       => ['sometimes', 'required', 'numeric', Rule::exists('users', 'id')],
            'company_id'    => ['sometimes', 'required', 'numeric', Rule::exists('companies', 'id')]
        ];
    }
}
