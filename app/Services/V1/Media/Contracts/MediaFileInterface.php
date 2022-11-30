<?php

namespace App\Services\V1\Media\Contracts;

interface MediaFileInterface
{
    /**
     * @param $file
     * @param string $disk
     * @param string $directory
     * @return bool|string
     */
    public function upload($file, string $disk, string $directory): bool|string;

    /**
     * @param $file
     * @param string $disk
     * @param string $directory
     * @return array
     */
    public function store($file, string $disk, string $directory): array;
}
