<?php

namespace App\Exceptions;

class AuthorizationException extends HttpException
{
    public static function notBelongsToLandlordGroup()
    {
        return new static(403, 'Permission deny, the current user not belongs to landlord group.', 'Invalid User Group');
    }
}
