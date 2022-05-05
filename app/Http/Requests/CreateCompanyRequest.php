<?php

namespace App\Http\Requests;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCompanyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            /** Company */
            'name'              => ['required', 'string', 'unique:companies', 'max:255'],
            'logo'              => ['sometimes', new Media],
            'disabled'          => 'sometimes|required|boolean',

            /** Information */
            'users.*.user_id'   => ['sometimes', 'required', 'numeric', 'max:10', Rule::exists('users', 'id')],
        ];
    }
}
