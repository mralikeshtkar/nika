<?php declare(strict_types=1);

namespace App\Enums\Package;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static Active()
 * @method static static Inactive()
 */
final class PackageStatus extends Enum implements LocalizedEnum
{
    const Active = 0;
    const Inactive = 1;
}
