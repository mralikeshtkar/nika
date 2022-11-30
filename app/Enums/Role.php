<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static SUPER_ADMIN()
 * @method static static PERSONNEL()
 * @method static static RAHJOO()
 * @method static static RAHNAMA()
 * @method static static RAHYAB()
 * @method static static SUPPORT()
 * @method static static STOREKEEPER()
 * @method static static AGENT()
 */
final class Role extends Enum implements LocalizedEnum
{
    const SUPER_ADMIN = "super admin";

    const PERSONNEL = "personnel";

    const RAHJOO = "rahjoo";

    const RAHNAMA = "rahnama";

    const RAHYAB = "rahyab";

    const SUPPORT = "support";

    const STOREKEEPER = "storekeeper";

    const AGENT = "agent";
}
