<?php

namespace App\Exceptions;

use Exception;

class MunicipalityException extends Exception
{
    public static function exist()
    {
        return new static(400, 'This municipality is already exist.', 'Not allow');
    }
}
