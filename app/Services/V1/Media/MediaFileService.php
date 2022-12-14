<?php

namespace App\Services\V1\Media;

use App\Models\Media;
use App\Services\V1\Media\Contracts\MediaFileInterface;
use App\Services\V1\Media\FileService\DefaultMediaFileService;
use function resolve;

class MediaFileService
{
    /**
     * @var mixed
     */
    private mixed $file;

    /**
     * @var string
     */
    private string $type;

    /**
     * @param $file
     * @param string $type
     * @param string $disk
     * @param string $directory
     * @return mixed
     */
    public function store($file, string $type, string $disk, string $directory): mixed
    {
        $this->file = $file;
        $this->type = $type;
        return $this->getHandlerStorageService()->store($this->file, $disk, $directory);
    }

    /**
     * @param Media $media
     * @return mixed
     */
    public function delete(Media $media): mixed
    {
        $this->type = $media->type;
        return $this->getHandlerStorageService()->delete($media);
    }

    /**
     * @return mixed
     */
    public function getHandlerStorageService(): mixed
    {
        return resolve(config('media.handlers.' . $this->type, DefaultMediaFileService::class));
    }

}
