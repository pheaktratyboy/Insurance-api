<?php

namespace App\Http\Requests;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;

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
            'attachments'    => ['sometimes', new Media],
            'note'           => 'sometimes|required',
            'subscriber_id'  => 'sometimes|required',
        ];
    }
}
