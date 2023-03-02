<?php

namespace App\Http\Resources\V1\Question;

use App\Http\Resources\V1\Media\MediaResource;
use App\Http\Resources\V1\QuestionPointRahjoo\QuestionPointRahjooResoruce;
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
        return collect($this->resource)->when($this->resource->relationLoaded('files') && count($this->resource->files), function (Collection $collection) {
            $collection->put('files', collect($this->resource->files)->map(function ($item) {
                return collect($item)->when($item->relationLoaded('media') && !is_null($item->media), function (Collection $collection) use ($item) {
                    $collection->put('media', new MediaResource($item->media));
                });
            }));
        })->when(array_key_exists('created_at', $this->resource->getAttributes()), function (Collection $collection) {
            $collection->put('created_at', jalaliFormat($this->resource->created_at, 'j F Y'));
        })->when(array_key_exists('updated_at', $this->resource->getAttributes()), function (Collection $collection) {
            $collection->put('updated_at', jalaliFormat($this->resource->updated_at, 'j F Y'));
        })->when($this->resource->relationLoaded('answers'), function (Collection $collection) {
            $collection->put('answers', QuestionAnswerResource::collection($this->resource->answers));
        })->when($this->resource->relationLoaded('pivotRahjooPoints'), function (Collection $collection) {
            $collection->put('pivot_rahjoo_points', QuestionPointRahjooResoruce::collection($this->resource->pivotRahjooPoints));
        })->when($this->resource->relationLoaded('answerTypes'), function (Collection $collection) {
            $collection->put('answer_types', QuestionAnswerTypeResource::collection($this->resource->answerTypes));
        })->when($this->resource->relationLoaded('answerTypes') && array_key_exists('rahjoo_answers_count', $this->resource->getAttributes()), function (Collection $collection) {
            $collection->put('is_answered', count($this->resource->answerTypes) <= $this->resource->rahjoo_answers_count );
        })->when($this->resource->latest_answer_created_at && $this->resource->question_duration_start_start, function (Collection $collection) {
            dd(now()->diffInSeconds(now()->subDays()),now()->diffInSeconds(now()->subDays(2)));
            dd($this->resource->latest_answer_created_at,$this->resource->question_duration_start_start);

            $collection->put('updated_at', jalaliFormat($this->resource->updated_at, 'j F Y'));
        });
    }
}
