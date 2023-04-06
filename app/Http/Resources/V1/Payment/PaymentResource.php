<?php

namespace App\Http\Resources\V1\Payment;

use App\Enums\Payment\PaymentStatus;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class PaymentResource extends JsonResource
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
        })->when(array_key_exists('date', $this->resource->getAttributes()), function (Collection $collection) {
            $collection->put('date', verta($this->resource->date)->formatJalaliDatetime());
        })->when(array_key_exists('status', $this->resource->getAttributes()), function (Collection $collection) {
            $collection->put('status_translated', PaymentStatus::fromValue($this->resource->status)->description);
        })->when(array_key_exists('gateway', $this->resource->getAttributes()), function (Collection $collection) {
            $collection->put('gateway', trans(ucfirst($this->resource->gateway)));
        });
    }
}
