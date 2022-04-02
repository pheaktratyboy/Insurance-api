<?php

namespace App\Http\Requests;

use App\Enums\Religion;
use App\Enums\Gender;
use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSubscriberRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_kh'               => ['required', 'string', 'max:255'],
            'name_en'               => ['required', 'string', 'max:255'],
            'identity_number'       => ['required', 'string', 'max:255'],
            'date_of_birth'         => ['required', 'date'],
            'phone_number'          => ['required', 'string', 'max:255'],
            'address'               => ['required', 'string', 'max:255'],
            'place_of_birth'        => ['required', 'string', 'max:255'],
            'gender'                => ['required', Rule::in(Gender::getValues())],
            'religion'              => ['required', Rule::in(Religion::getValues())],

            'avatar'                => ['sometimes', new Media],
            'id_or_passport_front'  => ['sometimes', new Media],
            'id_or_passport_back'   => ['sometimes', new Media],
            'attachments'           => 'sometimes',

            'note'                  => ['sometimes', 'required'],

            'policy_id'             => ['required', 'max:10', Rule::exists('policies', 'id')],
            'payment_method'        => ['required', 'string', 'max:255'],

            'company_id'            => ['max:10', Rule::exists('companies', 'id')],
        ];
    }
}
