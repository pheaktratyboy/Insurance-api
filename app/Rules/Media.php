<?php

namespace App\Rules;

use App\Traits\CustomRuleMessage;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class Media implements Rule
{
    use CustomRuleMessage;
    protected $message = 'Invalid media structure.';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        /** when request does not have value then it will be pass */
        if (!$value) {
            return true;
        }

        /** if request has data, it mean we can check validation */
        if (!is_array($value)) {
            $this->message = 'Media must be object format of id and url';
            return false;
        }

        $validator = Validator::make($value, [
            'id'  => 'required',
            'url' => 'required',
        ]);
        if ($validator->fails()) {
            $this->message = $this->formatMessage($validator->getMessageBag());
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
