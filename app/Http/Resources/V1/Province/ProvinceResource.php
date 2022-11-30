<?php

namespace App\Http\Resources\V1\Province;

use App\Http\Resources\V1\City\CityResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use JsonSerializable;

class ProvinceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request): array|JsonSerializable|Arrayable
    {
        return collect([
            'id' => $this->id,
            'name' => $this->name,
        ])->when($this->resource->relationLoaded('cities'), function (Collection $collection) {
            $collection->put('cities',CityResource::collection($this->resource->cities));
        })->toArray();
    }
}
