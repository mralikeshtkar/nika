<?php declare(strict_types=1);

namespace App\Enums\Personnel;

use BenSampo\Enum\Enum;

/**
 * @method static static LOW()
 * @method static static MID()
 * @method static static HIGH()
 */
final class PersonnelComputerLevel extends Enum
{
    const LOW = "low";
    const MID = "mid";
    const HIGH = "high";
}
