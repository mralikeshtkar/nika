<?php

namespace App\Http\Resources\V1\User;

use App\Enums\UserStatus;
use App\Http\Resources\V1\Personnel\PersonnelResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param $request
     * @return array
     */
    public function toArray($request)
    {
        return collect($this->resource)->when($this->resource->originalIsEquivalent('status'), function (Collection $collection) {
            $status = UserStatus::coerce($this->resource->status);
            $collection->put('status', $status->key)->put('status_translated', $status->description);
        })->toArray();
    }
}
