<?php

namespace App\Http\Resources\V1\Personnel;

use App\Http\Resources\V1\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class PersonnelResource extends JsonResource
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
            'major_id' => $this->resource->major_id,
            'job_id' => $this->resource->job_id,
            'birth_certificate_place_id' => $this->resource->birth_certificate_place_id,
            'is_married' => $this->resource->is_married,
            'email' => $this->resource->email,
            'language_level' => $this->resource->language_level,
            'computer_level' => $this->resource->computer_level,
            'research_history' => $this->resource->research_history,
            'is_working' => $this->resource->is_working,
            'work_description' => $this->resource->work_description,
        ])->when($this->resource->relationLoaded('user'),function (Collection $collection){
            $collection->put('user',UserResource::make($this->resource->user));
        })->toArray();
    }
}
