<?php

use App\Enums\Media\MediaExtension;
use App\Services\V1\Media\FileService\ImageMediaFileService;
use App\Services\V1\Media\FileService\VideoMediaFileService;
use App\Services\V1\Media\FileService\AudioMediaFileService;

return [
    'extensions' => [
        MediaExtension::Image => ['jpg', 'jpeg', 'png', 'gif'],
        MediaExtension::Video => ['mp4', 'mov', 'mpeg', 'mkv', 'wmv', 'avi'],
        MediaExtension::Document => ['doc', 'docx', 'dotx', 'pdf', 'odt', 'xls', 'xlsm', 'xlsx', 'ppt', 'pptx', 'vsd'],
        MediaExtension::Audio => ['mp3', 'ogg', 'flac'],
    ],
    'handlers' => [
        MediaExtension::Image => ImageMediaFileService::class,
        MediaExtension::Video => VideoMediaFileService::class,
        MediaExtension::Audio => AudioMediaFileService::class,
    ],
];
