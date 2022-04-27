<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMunicipalityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          => ['sometimes', 'required', 'max:255', Rule::unique('municipalities', 'name')->ignore($this->route('municipality')->id)],
            'disabled'      => 'sometimes|required|boolean',
        ];
    }
}
