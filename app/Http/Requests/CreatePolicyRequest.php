<?php

namespace App\Http\Requests;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;

class CreatePolicyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          => ['required', 'string', 'unique:policies', 'max:255'],
            'price'         => ['required', 'numeric', 'between:0,99999999.999'],
            'duration'      => ['required', 'max:10'],
            'logo'          => ['sometimes', new Media],
        ];
    }
}
