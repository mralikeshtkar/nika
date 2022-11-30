<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class PaginationResource extends JsonResource
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
            'perPage' => $this->resource->perPage(),
            'current_page' => $this->resource->currentPage(),
            'last_page' => $this->resource->lastPage(),
            'total' => $this->resource->total(),
            'onFirstPage' => $this->resource->onFirstPage(),
            'data' => $this->additional['itemsResource']::collection($this->resource->items())
        ];
    }
}
