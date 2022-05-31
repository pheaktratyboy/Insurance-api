<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Class AccidentType
 * @package App\Enum
 */
final class AccidentType extends Enum
{
    const DiedByDisease  = 'died_by_disease';
    const DiedByAccident = 'died_by_accident';
}
