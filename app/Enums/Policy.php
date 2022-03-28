<?php

use BenSampo\Enum\Enum;

/**
 * @method static static Employee()
 * @method static static Agency()
 */
final class Policy extends Enum
{
    const Staff  = 1;
    const Agency  = 2;
}
