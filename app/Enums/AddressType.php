<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static Home()
 * @method static static Office()
 */
final class AddressType extends Enum implements LocalizedEnum
{
    const Home = 0;
    const Office = 1;
}
