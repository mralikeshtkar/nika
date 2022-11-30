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
        $status = UserStatus::coerce($this->resource->status);
        return collect([
            'id' => $this->resource->id,
            'first_name' => $this->resource->first_name,
            'last_name' => $this->resource->last_name,
            'father_name' => $this->resource->father_name,
            'mobile' => $this->resource->mobile,
            'national_code' => $this->resource->national_code,
            'birthdate' => jalaliFormat($this->resource->birthdate, User::BIRTHDATE_VALIDATION_FORMAT),
            'status' => $status->key,
            'status_translated' => $status->description,
            'grade_id' => $this->resource->grade_id,
            'city_id' => $this->resource->city_id,
            'birth_place_id' => $this->resource->birth_place_id,
        ])->when($this->resource->originalIsEquivalent('grade_name'), function (Collection $collection) {
            $collection->put('grade_name', $this->resource->grade_name);
        })->when($this->resource->originalIsEquivalent('city_name'), function (Collection $collection) {
            $collection->put('city_name', $this->resource->city_name);
        })->when($this->resource->relationLoaded('province'), function (Collection $collection) {
            $collection->put('province', $this->resource->province);
        })->when($this->resource->originalIsEquivalent('birth_place_name'), function (Collection $collection) {
            $collection->put('birth_place_name', $this->resource->birth_place_name);
        })->when($this->resource->relationLoaded('birth_place_province'), function (Collection $collection) {
            $collection->put('birth_place_province', $this->resource->birth_place_province);
        })->when($this->resource->relationLoaded('personnel'),function (Collection $collection){
            $collection->put('personnel',PersonnelResource::make($this->resource->personnel));
        })->toArray();
    }
}
