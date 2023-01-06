<?php

namespace App\Http\Resources\V1\Package;

use App\Http\Resources\V1\Media\MediaVideoResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class PackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return collect($this->resource->getAttributes())
            ->when($this->whenAppended('description'), function (Collection $collection) {
                $collection->put('description', $this->resource->description);
            })->when($this->whenAppended('created_at'), function (Collection $collection) {
                $collection->put('created_at', verta($this->resource->created_at)->format("j F Y"));
            })->when($this->whenAppended('status'), function (Collection $collection) {
                $collection->put('translated_status', $this->resource->getTranslatedStatus());
            })->when($this->resource->relationLoaded('video'), function (Collection $collection) {
                $collection->put('video', new MediaVideoResource($this->resource->video));
            });
    }
}
