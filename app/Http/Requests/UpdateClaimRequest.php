<?php

namespace App\Http\Requests;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClaimRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'subject'        => ['sometimes', 'required', 'string', 'max:255'],
            'attachments.*'  => ['sometimes', 'array', new Media],
            'accident_type'  => ['sometimes', 'required', 'string'],
            'subscriber_id'  => ['sometimes', 'required', Rule::exists('subscribers', 'id')],
        ];
    }
}
