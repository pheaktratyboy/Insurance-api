<?php

namespace App\Exceptions;

use Exception;

class AuthenticationFailedException extends HttpException
{
    public static function invalidCredential(): AuthenticationFailedException
    {
        return new static(401, 'The user credentials were incorrect.', 'Invalid credential');
    }

    public static function accountNotInActivated(): AuthenticationFailedException
    {
        return new static(401, 'The user is not yet activated. Please contact to admin.', 'Account not activated');
    }
}
