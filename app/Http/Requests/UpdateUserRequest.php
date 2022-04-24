<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use App\Enums\Religion;
use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'email'                 => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users')->ignore(request()->route('user')->id)],
            'force_change_password' => 'sometimes|required|boolean',
            'disabled'              => 'sometimes|required|boolean',
            'activated'             => 'sometimes|required|boolean',

            /** Information */
            'name_kh'               => ['sometimes', 'required', 'string', 'max:255'],
            'name_en'               => ['sometimes', 'required', 'string', 'max:255'],
            'date_of_birth'         => ['sometimes', 'required', 'date'],
            'phone_number'          => ['sometimes', 'required', 'string', 'max:255'],
            'address'               => ['sometimes', 'required', 'string', 'max:255'],
            'place_of_birth'        => ['sometimes', 'required', 'string', 'max:255'],
            'gender'                => ['sometimes', 'required', Rule::in(Gender::getValues())],
            'religion'              => ['sometimes', 'required', Rule::in(Religion::getValues())],
        ];
    }
}
