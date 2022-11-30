<?php

namespace App\Http\Resources\V1\RahjooCourse;

use App\Http\Resources\V1\Rahjoo\RahjooResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class RahjooCourseResource extends JsonResource
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
            'rahjoo_id' => $this->resource->rahjoo_id,
            'name' => $this->resource->name,
            'duration' => $this->resource->duration,
        ])->when($this->resource->relationLoaded('rahjoo'),function (Collection $collection){
            $collection->put('rahjoo',RahjooResource::make($this->resource->rahjoo));
        })->toArray();
    }

}
