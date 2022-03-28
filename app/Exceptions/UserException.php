<?php


namespace App\Exceptions;

namespace App\Exceptions;

class UserException extends HttpException
{
    public static function usernameExist()
    {
        return new static(400, 'User already exist', 'Not allow.');
    }
}
