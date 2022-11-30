<?php declare(strict_types=1);

namespace App\Enums\Media;

use BenSampo\Enum\Enum;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;

/**
 * @method static static Image()
 * @method static static Video()
 * @method static static Document()
 * @method static static Default()
 */
final class MediaExtension extends Enum
{
    const Image = "image";
    const Video = "video";
    const Document = "document";
    const Default = "default";

    /**
     * @return Repository|Application|mixed
     */
    public static function getExtensions($value): mixed
    {
        return config('media.extensions.' . $value, []);
    }

}
