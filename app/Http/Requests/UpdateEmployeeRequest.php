<?php

namespace App\Http\Requests;

use App\Enums\Religion;
use App\Enums\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
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
            'username'              => ['sometimes', 'required', 'string', 'max:255'],
            'role_id'               => ['sometimes', 'required', Rule::exists('roles', 'id')],

            /** Information */
            'name_kh'               => ['sometimes', 'required', 'string', 'max:255'],
            'name_en'               => ['sometimes', 'required', 'string', 'max:255'],
            'identity_number'       => ['sometimes', 'required', 'string', 'max:255'],
            'date_of_birth'         => ['sometimes', 'required', 'date'],
            'phone_number'          => ['sometimes', 'required', 'string', 'max:255'],
            'address'               => ['sometimes', 'required', 'string', 'max:255'],
            'place_of_birth'        => ['sometimes', 'required', 'string', 'max:255'],
            'gender'                => ['sometimes', 'required', Rule::in(Gender::getValues())],
            'religion'              => ['sometimes', 'required', Rule::in(Religion::getValues())],
            'avatar_url'            => ['sometimes', 'required', 'string'],
            'id_or_passport_front'  => ['sometimes', 'required', 'string'],
            'id_or_passport_back'   => ['sometimes', 'required', 'string'],

            'kpi'                   => ['sometimes', 'required', 'numeric'],
            'commission'            => ['sometimes', 'required', 'numeric'],

            'municipality_id'       => ['sometimes', 'required', 'numeric', 'max:10', Rule::exists('municipalities', 'id')],
            'district_id'           => ['sometimes', 'required', 'numeric', 'max:10', Rule::exists('districts', 'id')],
        ];
    }
}
