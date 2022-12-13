<?php

namespace App\Http\Resources\V1\IntelligencePointName;

use Illuminate\Http\Resources\Json\JsonResource;

class IntelligencePointNameResource extends JsonResource
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
            'name' => $this->resource->name,
        ]);
    }

}
