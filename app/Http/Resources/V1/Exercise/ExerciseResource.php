<?php

namespace App\Http\Resources\V1\Exercise;

use App\Http\Resources\V1\Media\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class ExerciseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return collect($this->resource)->when(array_key_exists('created_at',$this->resource->getAttributes()), function (Collection $collection) {
            $collection->put('created_at', jalaliFormat($this->resource->created_at, 'j F Y'));
        })->when(array_key_exists('updated_at',$this->resource->getAttributes()), function (Collection $collection) {
            $collection->put('updated_at', jalaliFormat($this->resource->updated_at, 'j F Y'));
        })->when($this->resource->relationLoaded('files'),function (Collection $collection){
            $collection->put('files', is_array($this->resource->files) && count($this->resource->files) ? MediaResource::collection($this->resource->files) : null);
        })->when(array_key_exists('question_answers_count', $this->resource->getAttributes()) && array_key_exists('questions_count', $this->resource->getAttributes()), function (Collection $collection) {
            $collection->put('is_answered_questions', $this->resource->question_answers_count == $this->resource->questions_count);
        });
    }
}
