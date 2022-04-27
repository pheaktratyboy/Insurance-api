<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Class BaseRole
 * @package App\Enum
 */
final class BaseRole extends Enum
{
    const Master   = 'master';
    const Admin    = 'admin';
    const Staff  = 'staff';
    const Agency  = 'agency';
    const Subscriber  = 'subscriber';
}
