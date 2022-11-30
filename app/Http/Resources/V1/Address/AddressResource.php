<?php

namespace App\Http\Resources\V1\Address;

use App\Enums\AddressType;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use JsonSerializable;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param $request
     * @return array|Arrayable|JsonSerializable|mixed[]
     */
    public function toArray($request)
    {
        return collect([
            'id' => $this->resource->id,
            'user_id' => $this->resource->user_id,
            'city_id' => $this->resource->city_id,
            'type_id' => $this->resource->type,
            'address' => $this->resource->address,
            'postal_code' => $this->resource->postal_code,
            'phone_number' => $this->resource->phone_number,
            'type' => AddressType::getDescription($this->resource->type),
            'created_at' => jalaliFormat($this->resource->created_at),
        ])->when($this->resource->originalIsEquivalent('province_name'), function (Collection $collection) {
            $collection->put('province_name', $this->resource->province_name);
        })->when($this->resource->originalIsEquivalent('city_name'), function (Collection $collection) {
            $collection->put('city_name', $this->resource->city_name);
        })->toArray();
    }
}
