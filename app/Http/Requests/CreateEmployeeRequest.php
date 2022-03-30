<?php

namespace App\Http\Requests;

use App\Enums\Religion;
use App\Enums\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateEmployeeRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            /** Account */
            'username'              => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'unique:users', 'max:255'],
            'password'              => ['required', 'string', 'min:6'],

            /** Information */
            'name_kh'               => ['required', 'string', 'max:255'],
            'name_en'               => ['required', 'string', 'max:255'],
            'identity_number'       => ['required', 'string', 'max:255'],
            'date_of_birth'         => ['required', 'date'],
            'phone_number'          => ['required', 'string', 'max:255'],
            'address'               => ['required', 'string', 'max:255'],
            'place_of_birth'        => ['required', 'string', 'max:255'],
            'gender'                => ['required', Rule::in(Gender::getValues())],
            'religion'              => ['required', Rule::in(Religion::getValues())],
            'avatar_url'            => ['required', 'string'],
            'id_or_passport_front'  => ['required', 'string'],
            'id_or_passport_back'   => ['required', 'string'],
        ];
    }
}
