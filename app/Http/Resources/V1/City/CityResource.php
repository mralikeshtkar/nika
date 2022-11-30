<?php

namespace App\Http\Resources\V1\City;

use App\Models\City;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param $request
     * @return array
     */
    public function toArray($request)
    {
        return collect([
            'id' => $this->resource->id,
            'name' => $this->resource->name,
        ])->when($this->resource->originalIsEquivalent('province_id'), function (Collection $collection) {
            $collection->put('province_id',$this->resource->province_id);
        })->when($this->resource->originalIsEquivalent('province_name'),function (Collection $collection){
            $collection->put('province_name',$this->resource->province_name);
        })->toArray();
    }
}
