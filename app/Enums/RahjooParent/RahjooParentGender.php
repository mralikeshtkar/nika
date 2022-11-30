<?php declare(strict_types=1);

namespace App\Enums\RahjooParent;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static Male()
 * @method static static Female()
 */
final class RahjooParentGender extends Enum implements LocalizedEnum
{
    const Male = 0;
    const Female = 1;
}
