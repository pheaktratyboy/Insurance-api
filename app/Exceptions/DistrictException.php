<?php

namespace App\Exceptions;

use Exception;

class DistrictException extends Exception
{
    public static function exist()
    {
        return new static(400, 'This district is already exist.', 'Not allow');
    }
}
