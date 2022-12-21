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
        return collect($this->resource)->when(collect($this->resource)->has('status'), function (Collection $collection) {
            $status = UserStatus::coerce(intval($this->resource->status));
            $collection->put('status', $status->key)->put('status_translated', $status->description);
        })->when(collect($this->resource)->has('birthdate'), function (Collection $collection) {
            $collection->put('birthdate', jalaliFormat($this->resource->birthdate, User::BIRTHDATE_VALIDATION_FORMAT));
        })->when(collect($this->resource)->has('grade_name'), function (Collection $collection) {
            $collection->put('grade_name', $this->resource->grade_name);
        })->when(collect($this->resource)->has('city_name'), function (Collection $collection) {
            $collection->put('city_name', $this->resource->city_name);
        })->when($this->resource->relationLoaded('province'), function (Collection $collection) {
            $collection->put('province', $this->resource->province);
        })->when(collect($this->resource)->has('birth_place_name'), function (Collection $collection) {
            $collection->put('birth_place_name', $this->resource->birth_place_name);
        })->when($this->resource->relationLoaded('birth_place_province'), function (Collection $collection) {
            $collection->put('birth_place_province', $this->resource->birth_place_province);
        })->when($this->resource->relationLoaded('personnel'), function (Collection $collection) {
            $collection->put('personnel', PersonnelResource::make($this->resource->personnel));
        })->toArray();
    }
}
