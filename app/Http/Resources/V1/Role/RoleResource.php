<?php

namespace App\Http\Resources\V1\Role;

use App\Http\Resources\V1\Permission\PermissionResource;
use App\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param $request
     * @return array
     */
    public function toArray($request)
    {
        return collect([
            'id' => $this->id,
            'name' => $this->name,
            'name_fa' => $this->name_fa,
            'is_locked' => $this->is_locked,
        ])->when($this->resource->relationLoaded('permissions'), function (Collection $collection) {
            $collection->put('permissions', PermissionResource::collection($this->permissions));
        })->toArray();
    }
}
