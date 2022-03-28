<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateDistrictRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'municipality_id' => ['required',Rule::exists('municipalities', 'id')],
            'name'            => 'required|string|max:255',
        ];
    }
}
