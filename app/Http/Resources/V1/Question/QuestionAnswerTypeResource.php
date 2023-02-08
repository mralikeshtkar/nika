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
        return collect($this->resource)->when($this->resource->relationLoaded('answers'), function (Collection $collection) {
            $collection->put('answers', QuestionAnswerResource::collection($this->resource->answers));
        });
    }
}
