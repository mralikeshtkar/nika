<?php

namespace App\Services\V1\Media\FileService;

use Illuminate\Support\Facades\Storage;

class BaseFileService
{
    /**
     * @param $file
     * @param string $disk
     * @param string $directory
     * @return bool|string
     */
    public function upload($file, string $disk, string $directory): bool|string
    {
        if (is_resource($file))
            return Storage::disk($disk)->put($directory, $file);
        return Storage::disk($disk)->putFileAs($directory, $file, uniqid() . time() . "." . $file->getClientOriginalExtension());
    }

    /**
     * @param $file
     * @param string $disk
     * @param string $directory
     * @return array
     */
    public function store($file, string $disk, string $directory): array
    {
        return [
            'original' => $this->upload($file, $disk, $directory),
        ];
    }
}
