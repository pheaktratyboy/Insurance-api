<?php

namespace App\Http\Requests;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateClaimRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'subject'        => ['required', 'string', 'max:255'],
            'attachments'    => ['sometimes', new Media],
            'subscriber_id'  => ['sometimes', 'required', Rule::exists('subscribers', 'id')],
        ];
    }
}
