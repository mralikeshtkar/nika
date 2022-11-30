<?php

namespace App\Http\Resources\V1\Rahjoo;

use App\Http\Resources\V1\RahjooParent\RahjooParentResource;
use App\Http\Resources\V1\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class RahjooResource extends JsonResource
{
    /**
     * @param $request
     * @return array
     */
    public function toArray($request)
    {
        return collect([
            'id' => $this->resource->id,
            'user_id' => $this->resource->user_id,
            'school' => $this->resource->school,
            'which_child_of_family' => $this->resource->which_child_of_family,
            'disease_background' => $this->resource->disease_background,
            'created_at' => jalaliFormat($this->resource->created_at),
        ])->when($this->resource->relationLoaded('user'), function (Collection $collection) {
            $collection->put('user', UserResource::make($this->resource->user));
        })->when($this->resource->relationLoaded('father'), function (Collection $collection) {
            $collection->put('father', RahjooParentResource::make($this->resource->father));
        })->when($this->resource->relationLoaded('mother'), function (Collection $collection) {
            $collection->put('mother', RahjooParentResource::make($this->resource->mother));
        })->toArray();
    }
}
