<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAgencyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            /** account */
            'username'     => ['required','string','unique:users'],
            'email'        => ['required','email','unique:users'],
            'password'     => 'required|min:6',
            'force_update' => 'required|boolean',

            /** profile */
            'name_kh'       => 'required|string',
            'name_en'       => 'required|string',
            'primary_phone' => 'sometimes|phone:KH',
        ];
    }
}
