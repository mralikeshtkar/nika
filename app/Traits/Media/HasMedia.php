<?php

namespace App\Traits\Media;

use App\Enums\Media\MediaExtension;
use App\Models\Media;
use App\Services\V1\Media\MediaFileService;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Http\UploadedFile;

trait HasMedia
{
    use HasRelationships;

    /**
     * @var mixed
     */
    private mixed $collection = null;

    /**
     * Default disk is public.
     *
     * @var string
     */
    private string $disk = "public";

    /**
     * @var mixed
     */
    private mixed $extension;

    /**
     * @var mixed
     */
    private mixed $base_url;

    /**
     * @var mixed
     */
    private mixed $directory = "";

    /**
     * @var mixed
     */
    private mixed $type;

    /**
     * @return mixed
     */
    public function getCollection(): mixed
    {
        return $this->collection;
    }

    /**
     * @param mixed $collection
     * @return $this
     */
    public function setCollection(mixed $collection): static
    {
        $this->collection = $collection;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisk(): string
    {
        return $this->disk;
    }

    /**
     * @param string $disk
     * @return $this
     */
    public function setDisk(string $disk): static
    {
        $this->disk = $disk;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExtension(): mixed
    {
        return $this->extension;
    }

    /**
     * @param mixed $extension
     * @return $this
     */
    public function setExtension(mixed $extension): static
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBaseUrl(): mixed
    {
        return $this->base_url;
    }

    /**
     * @param mixed $base_url
     * @return $this
     */
    public function setBaseUrl(mixed $base_url): static
    {
        $this->base_url = $base_url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDirectory(): mixed
    {
        return $this->directory;
    }

    /**
     * @param mixed $directory
     * @return $this
     */
    public function setDirectory(mixed $directory): static
    {
        $this->directory = $directory;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType(): mixed
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return $this
     */
    public function setType(mixed $type): static
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return MorphMany
     */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }

    /**
     * @return MorphOne
     */
    public function singleMedia(): MorphOne
    {
        return $this->morphOne(Media::class, 'model');
    }

    public function addMedia(UploadedFile $file)
    {
        $extension = strtolower($file->extension());
        $this->setType($this->getFileType($extension))
            ->setExtension($extension)
            ->setBaseUrl(url('/'));
        $this->storeModel(resolve(MediaFileService::class)->store($file, $this->getType(), $this->getDisk(), $this->getDirectory()));
    }

    /**
     * @param $files
     * @return Model
     */
    private function storeModel($files): Model
    {
        return $this->media()->create([
            'user_id' => auth()->id(),
            'disk' => $this->getDisk(),
            'files' => $files,
            'extension' => $this->getExtension(),
            'type' => $this->getType(),
            'collection' => $this->getCollection(),
            'base_url' => $this->getBaseUrl(),
        ]);
    }

    /**
     * Get file type.
     *
     * @param $extension
     * @return mixed
     */
    private function getFileType($extension): mixed
    {
        return collect(MediaExtension::asArray())->first(function ($item) use ($extension) {
            return in_array($extension, MediaExtension::getExtensions($item));
        }, MediaExtension::Default);
    }
}
