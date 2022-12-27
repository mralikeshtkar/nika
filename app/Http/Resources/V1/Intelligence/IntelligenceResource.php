<?php

namespace App\Http\Resources\V1\Intelligence;

use App\Models\Intelligence;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class IntelligenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return collect($this->resource)->when($this->resource->pivot,function (Collection $collection){
            $collection->put('pivot',$this->resource->pivot);
        });
    }
}
