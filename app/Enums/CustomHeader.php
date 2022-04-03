<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Class CustomHeader
 * @package App\Enum
 */

final class CustomHeader extends Enum
{
    const DeviceInformation = 'x-device-information';
    const PushToken         = 'x-push-token';
    const Company           = 'x-company-id';
}
