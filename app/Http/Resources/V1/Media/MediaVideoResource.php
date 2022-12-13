<?php

namespace App\Http\Resources\V1\Media;

use App\Models\Media;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaVideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            /*'model_type' => $this->resource->model_type,
            'model_id' => $this->resource->model_id,
            'disk' => $this->resource->disk,*/
            'extension' => $this->resource->extension,
            'type' => $this->resource->type,
            'is_private' => $this->resource->isDiskPrivate(),
            'files' => $this->resource->isDiskPrivate() ? [] : $this->_publicURLFiles(),
        ];
    }

    /**
     * @return array
     */
    private function _publicURLFiles(): array
    {
        return match ($this->resource->disk) {
            Media::MEDIA_PRIVATE_DISK => [],
            default => collect($this->resource->files)->map(function ($item) {
                return $this->resource->base_url . '/storage/' . $item;
            })->toArray(),
        };
    }


}
