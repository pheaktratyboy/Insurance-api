<?php

namespace App\Http\Requests;

use App\Enums\Religion;
use App\Enums\Gender;
use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            /** Account */
            'email'                 => ['required', 'email', 'unique:users', 'max:255'],
            'password'              => 'required|min:6',
            'force_change_password' => 'required|boolean',

            /** Information */
            'name_kh'               => ['required', 'string', 'max:255'],
            'name_en'               => ['required', 'string', 'max:255'],
            'identity_number'       => ['required', 'string', 'min:9', 'max:10', 'unique:employees'],
            'date_of_birth'         => ['required', 'date'],
            'phone_number'          => ['required', 'string', 'max:255'],
            'address'               => ['required', 'string', 'max:255'],
            'place_of_birth'        => ['required', 'string', 'max:255'],
            'gender'                => ['required', Rule::in(Gender::getValues())],
            'religion'              => ['required', Rule::in(Religion::getValues())],

            'avatar'                => ['sometimes', new Media],
            'id_or_passport_front'  => ['sometimes', new Media],
            'id_or_passport_back'   => ['sometimes', new Media],

            'kpi'                   => ['required', 'numeric'],
            'commission'            => ['sometimes', 'required', 'numeric'],

            'municipality_id'       => ['required', 'numeric', Rule::exists('municipalities', 'id')],
            'district_id'           => ['required', 'numeric', Rule::exists('districts', 'id')],
        ];
    }
}
