<?php

use Spatie\Enum\Enum;

/**
 * Class StatusType
 * @package App\Enum
 * @method static self pending()
 * @method static self cancel()
 * @method static self approved()
 * @method static self rejected()
 *
 * @method static bool isPending(int|string $value = null)
 * @method static bool isApproved(int|string $value = null)
 * @method static bool isRejected(int|string $value = null)
 */
class StatusType extends Enum
{
    const Pending   = 'pending';
    const Cancel = 'cancel';
    const Approved  = 'approved';
    const Rejected  = 'rejected';
}
