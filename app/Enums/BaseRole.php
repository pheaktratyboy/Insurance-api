<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class BaseRole extends Enum
{
    const Master   = 'master';
    const Admin    = 'admin';
    const Staff  = 'staff';
    const Agency  = 'agency';
}
