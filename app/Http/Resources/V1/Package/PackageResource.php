<?php

namespace App\Http\Resources\V1\Package;

use App\Http\Resources\V1\Media\MediaResource;
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
        return collect([
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'age' => $this->resource->age,
            'price' => intval($this->resource->price),
            'is_completed' => $this->resource->is_completed,
        ])->when($this->resource->originalIsEquivalent('description'), function (Collection $collection) {
            $collection->put('description', $this->resource->description);
        })->when($this->resource->relationLoaded('video'), function (Collection $collection) {
            $collection->put('video', new MediaResource($this->resource->video));
        })->toArray();
    }
}
