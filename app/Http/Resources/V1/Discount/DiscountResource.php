<?php

namespace App\Http\Resources\V1\Discount;

use App\Enums\Discount\DiscountStatus;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class DiscountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return collect($this->resource)->when(array_key_exists('created_at', $this->resource->getAttributes()), function (Collection $collection) {
            $collection->put('created_at', verta($this->resource->created_at)->formatJalaliDate());
        })->when(array_key_exists('status', $this->resource->getAttributes()), function (Collection $collection) {
            $collection->put('translated_status', DiscountStatus::getDescription($this->resource->status));
        })->when(array_key_exists('enable_at', $this->resource->getAttributes()) && !is_null($this->resource->enable_at), function (Collection $collection) {
            $collection->put('enable_at', verta($this->resource->enable_at)->formatJalaliDate());
        })->when(array_key_exists('expire_at', $this->resource->getAttributes()) && !is_null($this->resource->expire_at), function (Collection $collection) {
            $collection->put('expire_at', verta($this->resource->enable_at)->formatJalaliDate());
        });
    }
}
