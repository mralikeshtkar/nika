<?php

namespace App\Http\Resources\V1\PsychologicalQuestion;

use App\Http\Resources\V1\Skill\SkillResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class PsychologicalQuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return collect([
            'id' => $this->resource->id,
            'user_id' => $this->resource->user_id,
            'rahjoo_id' => $this->resource->rahjoo_id,
            'favourite_job_id' => $this->resource->favourite_job_id,
            'parent_favourite_job_id' => $this->resource->parent_favourite_job_id,
            'negative_positive_points' => $this->resource->negative_positive_points,
            'favourites' => $this->resource->favourites,
        ])->when($this->resource->relationLoaded('skills'), function (Collection $collection) {
            $collection->put('skills', SkillResource::collection($this->resource->skills));
        })->toArray();
    }
}
