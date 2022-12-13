<?php

namespace App\Observers\V1\Media;

use App\Models\Media;
use App\Services\V1\Media\MediaFileService;

class MediaObserver
{
    /**
     * @param Media $media
     * @return void
     */
    public function deleted(Media $media)
    {
        resolve(MediaFileService::class)->delete($media);
    }
}
