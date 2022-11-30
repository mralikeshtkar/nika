<?php

namespace App\Services\V1\Media;

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

    public function store($file, string $type, string $disk, string $directory)
    {
        $this->file = $file;
        $this->type = $type;
        return $this->getHandlerStorageService()->store($this->file, $disk, $directory);
    }

    /**
     * @return mixed
     */
    public function getHandlerStorageService(): mixed
    {
        return resolve(config('media.handlers.' . $this->type, DefaultMediaFileService::class));
    }

}
