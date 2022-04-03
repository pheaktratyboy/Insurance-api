<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Class StatusType
 * @package App\Enum
 */
final class StatusType extends Enum
{
    const Pending   = 'pending';
    const Cancel = 'cancel';
    const Approved  = 'approved';
    const Rejected  = 'rejected';
    const Claimed  = 'claimed';
}
