<?php

namespace App\Http\Resources\V1\RahjooParent;

use App\Models\RahjooParent;
use Illuminate\Http\Resources\Json\JsonResource;

class RahjooParentResource extends JsonResource
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
            'rahjoo_id' => $this->resource->rahjoo_id,
            'job_id' => $this->resource->job_id,
            'grade_id' => $this->resource->grade_id,
            'major_id' => $this->resource->major_id,
            'name' => $this->resource->name,
            'mobile' => $this->resource->mobile,
            'birthdate' => $this->resource->birthdate ? jalaliFormat($this->resource->birthdate, RahjooParent::BIRTHDATE_VALIDATION_FORMAT) : null,
            'child_count' => $this->resource->child_count,
            'gender_id' => $this->resource->gender,
            'gender' => $this->resource->getTranslatedGender(),
        ])->toArray();
    }
}
