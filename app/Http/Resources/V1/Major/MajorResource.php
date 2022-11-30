<?php

namespace App\Http\Resources\V1\Major;

use Illuminate\Http\Resources\Json\JsonResource;

class MajorResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
        ];
    }
}
