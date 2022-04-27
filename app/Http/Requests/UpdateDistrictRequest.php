<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDistrictRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'municipality_id' => ['sometimes', Rule::exists('municipalities', 'id'), 'max:10'],
            'name'            => ['sometimes', 'required', 'string', 'unique:districts', 'max:255'],
            'disabled'        => 'sometimes|required|boolean',
        ];
    }
}
