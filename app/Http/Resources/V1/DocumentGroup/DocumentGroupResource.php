<?php

namespace App\Http\Resources\V1\DocumentGroup;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentGroupResource extends JsonResource
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
