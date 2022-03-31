<?php

namespace App\Http\Requests;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;

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
            'name'          => ['sometimes', 'required', 'string', 'unique:companies', 'max:255'],
            'logo'          => ['sometimes', new Media],
            'staff_count'   => ['max:10'],
        ];
    }
}
