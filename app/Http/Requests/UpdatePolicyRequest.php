<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePolicyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          => ['sometimes', 'required', 'max:255', Rule::unique('policies', 'name')->ignore($this->route('policy')->id)],
            'price'         => ['sometimes', 'required', 'numeric', 'between:0,99999999.999'],
            'duration'      => ['sometimes', 'max:10'],
        ];
    }
}
