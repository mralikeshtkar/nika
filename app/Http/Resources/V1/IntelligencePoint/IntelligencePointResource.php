<?php

namespace App\Http\Resources\V1\IntelligencePoint;

use Illuminate\Http\Resources\Json\JsonResource;

class IntelligencePointResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return collect($this->resource);
    }
}
