<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSubscriberPolicyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'policy_id'             => ['required', Rule::exists('policies', 'id')],
            'payment_method'        => ['required', 'string', 'max:255'],
        ];
    }
}
