<?php

namespace App\Http\Resources\V1\Question;

use App\Http\Resources\V1\Media\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return collect($this->resource)->when($this->resource->relationLoaded('files'), function (Collection $collection) {
            $collection->put('files', MediaResource::collection($this->resource->files));
        });
    }
}
