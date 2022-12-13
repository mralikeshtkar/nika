<?php

namespace App\Http\Resources\V1\IntelligenceFeedback;

use Illuminate\Http\Resources\Json\JsonResource;
use function collect;

class IntelligenceFeedbackResource extends JsonResource
{
    /**
     * @param $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\Illuminate\Support\Collection|\JsonSerializable
     */
    public function toArray($request)
    {
        return collect([
            'id' => $this->resource->id,
            'package_id' => $this->resource->package_id,
            'intelligence_id' => $this->resource->intelligence_id,
            'title' => $this->resource->title,
            'max_point' => $this->resource->max_point,
        ]);
    }
}
