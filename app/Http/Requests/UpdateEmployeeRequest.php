<?php

namespace App\Http\Requests;

use App\Enums\Religion;
use App\Enums\Gender;
use App\Rules\Media;
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
        $user = $this->route('user');

        return [
            /** Account */
            'email'                 => ['sometimes', 'required', 'email', 'max:255'],
            'role_id'               => ['sometimes', 'required', Rule::exists('roles', 'id')],
            'disabled'              => 'sometimes|required|boolean',
            'activated'             => 'sometimes|required|boolean',

            /** Information */
            'name_kh'               => ['sometimes', 'required', 'string', 'max:255'],
            'name_en'               => ['sometimes', 'required', 'string', 'max:255'],
            'identity_number'       => ['sometimes', 'required', 'string', 'min:9', 'max:10', Rule::unique('employees')->ignore($user->id)],
            'date_of_birth'         => ['sometimes', 'required', 'date'],
            'phone_number'          => ['sometimes', 'required', 'string', 'max:255'],
            'address'               => ['sometimes', 'required', 'string', 'max:255'],
            'place_of_birth'        => ['sometimes', 'required', 'string', 'max:255'],
            'gender'                => ['sometimes', 'required', Rule::in(Gender::getValues())],
            'religion'              => ['sometimes', 'required', Rule::in(Religion::getValues())],

            'avatar'                => ['sometimes', new Media],
            'id_or_passport_front'  => ['sometimes', new Media],
            'id_or_passport_back'   => ['sometimes', new Media],
            'attachments.*'         => ['sometimes', 'array', new Media],

            'kpi'                   => ['sometimes', 'required', 'numeric'],
            'commission'            => ['sometimes', 'required', 'numeric'],

            'municipality_id'       => ['sometimes', 'required', 'numeric', 'max:10', Rule::exists('municipalities', 'id')],
            'district_id'           => ['sometimes', 'required', 'numeric', 'max:10', Rule::exists('districts', 'id')],
        ];
    }
}
