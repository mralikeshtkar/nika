<?php

namespace App\Http\Resources\V1\Rahjoo;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class RahjooSupportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return collect($this->resource)->when(array_key_exists('created_at',$this->resource->toArray()), function (Collection $collection) {
            $collection->put('created_at', jalaliFormat($this->resource->created_at));
        });
    }
}
