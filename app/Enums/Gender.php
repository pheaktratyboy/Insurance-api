<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Male()
 * @method static static Female()
 * @method static static Other()
 */
final class Gender extends Enum
{
    const Male   = 'male';
    const Female = 'female';
    const Other  = 'other';
}
