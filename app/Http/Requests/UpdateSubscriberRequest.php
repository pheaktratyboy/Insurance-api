<?php

namespace App\Http\Requests;

use App\Enums\Category;
use App\Enums\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubscriberRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_kh'               => ['sometimes', 'string', 'max:255'],
            'name_en'               => ['sometimes', 'string', 'max:255'],
            'identity_number'       => ['sometimes', 'string', 'max:255'],
            'date_of_birth'         => ['sometimes', 'date'],
            'primary_phone'         => ['sometimes', 'string', 'max:255'],
            'address'               => ['sometimes', 'string', 'max:255'],
            'place_of_birth'        => ['sometimes', 'string', 'max:255'],
            'gender'                => [Rule::in(Gender::getValues())],
            'category'              => [Rule::in(Category::getValues())],
            'avatar_url'            => ['sometimes', 'string'],
            'id_or_passport_front'  => ['sometimes', 'string'],
            'id_or_passport_back'   => ['sometimes', 'string'],
            'policy_id'             => ['sometimes', 'max:10', Rule::exists('policies', 'id')],
            'payment_method'        => ['sometimes', 'string', 'max:255'],
        ];
    }
}
