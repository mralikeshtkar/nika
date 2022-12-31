<?php declare(strict_types=1);

namespace App\Enums\Question;

use BenSampo\Enum\Enum;

/**
 * @method static static Video()
 * @method static static Text()
 * @method static static Image()
 * @method static static Audio()
 */
final class QuestionAnswerType extends Enum
{
    const Video = "video";
    const Text = "text";
    const Image = "image";
    const Audio = "audio";
}
