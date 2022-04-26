<?php

namespace App\Http\Requests;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          => ['sometimes', 'required', 'string', 'max:255', Rule::unique('companies', 'name')->ignore($this->route('company')->id)],
            'logo'          => ['sometimes', new Media],

            'disabled'      => 'sometimes|required|boolean',
        ];
    }
}
