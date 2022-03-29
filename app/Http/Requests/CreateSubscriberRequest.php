<?php

namespace App\Http\Requests;

use App\Enums\Category;
use App\Enums\Gender;
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
            'primary_phone'         => ['required', 'string', 'max:255'],
            'address'               => ['required', 'string', 'max:255'],
            'place_of_birth'        => ['required', 'string', 'max:255'],
            'gender'                => [Rule::in(Gender::getValues())],
            'category'              => [Rule::in(Category::getValues())],
            'avatar_url'            => ['required', 'string'],
            'id_or_passport_front'  => ['required', 'string'],
            'id_or_passport_back'   => ['required', 'string'],
            'policy_id'             => ['required', 'max:10', Rule::exists('policies', 'id')],
            'payment_method'        => ['required', 'string', 'max:255'],
        ];
    }
}
