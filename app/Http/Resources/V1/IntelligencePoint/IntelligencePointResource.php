<?php

namespace App\Http\Resources\V1\IntelligencePoint;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class IntelligencePointResource extends JsonResource
{
    public static array $extra_additional = [];

    /**
     * @return array
     */
    public static function getExtraAdditional(): array
    {
        return self::$extra_additional;
    }

    /**
     * @param array $extra_additional
     */
    public static function setExtraAdditional(array $extra_additional): void
    {
        self::$extra_additional = $extra_additional;
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return collect($this->resource)->when(array_key_exists('withRemind', self::getExtraAdditional()), function (Collection $collection) {
            $point = self::getExtraAdditional()['withRemind']->firstWhere('intelligence_point_id', $this->id);
            $collection->put('remind', $point ? $this->max_point - $point->max_point_sum : $this->max_point);
        });
    }

    public static function collection($resource, $additional = [])
    {
        self::$extra_additional = $additional;
        return parent::collection($resource);
    }

}
