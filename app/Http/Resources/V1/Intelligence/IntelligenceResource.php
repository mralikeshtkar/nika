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
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return collect($this->resource)->when($this->resource->pivot, function (Collection $collection) {
            $pivot = collect($this->resource->pivot)->when(array_key_exists('created_at', $this->resource->pivot->getAttributes()), function (Collection $collection) {
                $collection->put('created_at', jalaliFormat($this->resource->pivot->created_at,'j F Y'));
            })->when(array_key_exists('updated_at', $this->resource->pivot->getAttributes()), function (Collection $collection) {
                $collection->put('updated_at', jalaliFormat($this->resource->pivot->updated_at,'j F Y'));
            });
            $collection->put('pivot', $pivot);
        });
    }
}
