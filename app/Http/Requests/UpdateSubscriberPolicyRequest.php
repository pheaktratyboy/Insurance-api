<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubscriberPolicyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'policy_id'             => ['sometimes', 'required', 'max:10', Rule::exists('policies', 'id')],
            'payment_method'        => ['sometimes', 'required', 'string', 'max:255'],
        ];
    }
}
