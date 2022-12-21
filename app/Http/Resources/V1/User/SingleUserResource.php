<?php

namespace App\Http\Resources\V1\User;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class SingleUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return collect($this->resource)->when(Arr::has($this->resource, 'birthdate'), function (Collection $collection) {
            $collection->put('birthdate', jalaliFormat($this->resource['birthdate'], User::BIRTHDATE_VALIDATION_FORMAT))
                ->put('age', calculateAgeYear($this->resource['birthdate']));
        });
    }
}
