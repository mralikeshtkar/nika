<?php

namespace App\Http\Resources\V1\Product;

use App\Enums\Product\ProductStatus;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class ProductResource extends JsonResource
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
            $collection->put('translated_status', ProductStatus::getDescription($this->resource->status));
        });
    }
}
