<?php

namespace App\Http\Resources\V1\Skill;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class SkillResource extends JsonResource
{
    /**
     * @param $request
     * @return array
     */
    public function toArray($request)
    {
        return collect([
            'id' => $this->resource->id,
            'title' => $this->resource->title,
        ])->when($this->resource->originalIsEquivalent('user_id'), function (Collection $collection) {
            $collection->put('user_id', $this->resource->user_id);
        })->toArray();
    }
}
