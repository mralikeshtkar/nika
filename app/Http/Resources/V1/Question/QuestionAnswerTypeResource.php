<?php

namespace App\Http\Resources\V1\Question;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class QuestionAnswerTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return collect($this->resource)->when($this->resource->relationLoaded('answer') && !is_null($this->resource->answer), function (Collection $collection) {
            $collection->put('answer', new QuestionAnswerResource($this->resource->answer));
        });
    }
}
