<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Muslim()
 * @method static static Non_Muslim()
 */
final class Category extends Enum
{
    const Muslim   = 'muslim';
    const Non_Muslim = 'non_muslim';
}
