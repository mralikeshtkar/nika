<?php

namespace App\Http\Resources\V1\Rahjoo;

use App\Http\Resources\V1\Question\QuestionAnswerResource;
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
        return collect($this->resource)->when(array_key_exists('created_at',$this->resource->toArray()), function (Collection $collection) {
            $collection->put('created_at', jalaliFormat($this->resource->created_at));
        })->when($this->resource->relationLoaded('user'), function (Collection $collection) {
            $collection->put('user', UserResource::make($this->resource->user));
        })->when($this->resource->relationLoaded('father'), function (Collection $collection) {
            $collection->put('father', RahjooParentResource::make($this->resource->father));
        })->when($this->resource->relationLoaded('mother'), function (Collection $collection) {
            $collection->put('mother', RahjooParentResource::make($this->resource->mother));
        })->when($this->resource->relationLoaded('answers'), function (Collection $collection) {
            $collection->put('answers', QuestionAnswerResource::collection($this->resource->answers));
        })->when($this->resource->relationLoaded('support'), function (Collection $collection) {
            $collection->put('support', new RahjooSupportResource($this->resource->support));
        })->when($this->resource->relationLoaded('support'), function (Collection $collection) {
            $collection->put('support', new RahjooSupportResource($this->resource->support));
        })->toArray();
    }
}
