<?php

namespace App\Http\Resources\V1\Order;

use App\Enums\Order\OrderStatus;
use App\Http\Resources\V1\Payment\PaymentResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class OrderResource extends JsonResource
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
            $collection->put('created_at', verta($this->resource->created_at)->formatJalaliDatetime());
        })->when(array_key_exists('sent_at', $this->resource->getAttributes()), function (Collection $collection) {
            $collection->put('sent_at', verta($this->resource->sent_at)->formatJalaliDatetime());
        })->when(array_key_exists('status', $this->resource->getAttributes()), function (Collection $collection) {
            $collection->put('translated_status', OrderStatus::getDescription($this->resource->status));
        })->when($this->resource->relationLoaded('payment'), function (Collection $collection) {
            $collection->put('payment', new PaymentResource($this->resource->payment));
        });
    }
}
