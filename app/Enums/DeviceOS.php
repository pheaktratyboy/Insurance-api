<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static iOS()
 * @method static static iPadOS()
 * @method static static Android()
 */
final class DeviceOS extends Enum
{
    const iOS     = 'iOS';
    const iPadOS  = 'iPadOS';
    const Android = 'Android';
}
