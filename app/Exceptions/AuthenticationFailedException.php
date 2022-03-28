<?php

namespace App\Exceptions;

class AuthenticationFailedException extends HttpException
{
    public static function invalidCredential(): AuthenticationFailedException
    {
        return new static(401, 'The user credentials were incorrect.', 'Invalid credential');
    }

    public static function accountNotInActivated(): AuthenticationFailedException
    {
        return new static(401, 'The user is not yet activated, please contact to admin..', 'Account not activated');
    }

    public static function invalidTenantProvide($message = null)
    {
        if (!$message) {
            $message = 'Unauthorized access to the provided partner information or partner might not be exists.';
        }
        return new static(400, $message, 'Failed to verify provide partner');
    }
    public static function invalidRequestHeaderCompany()
    {
        return new static(401, 'Please provide request header of company.', 'Invalid request header.');
    }
}
