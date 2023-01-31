<?php

use App\Enums\AddressType;
use App\Enums\Package\PackageStatus;
use App\Enums\Permission;
use App\Enums\Question\QuestionAnswerType;
use App\Enums\RahjooParent\RahjooParentGender;
use App\Enums\Role;

return [
    QuestionAnswerType::class => [
        QuestionAnswerType::Video => ['required', 'mimes:' . implode(',', config('media.extensions.video'))],
        QuestionAnswerType::Text => ['required', 'string'],
        QuestionAnswerType::Image => ['required', 'mimes:' . implode(',', config('media.extensions.image'))],
        QuestionAnswerType::Audio => ['required', 'mimes:' . implode(',', config('media.extensions.audio'))],
    ],
];
