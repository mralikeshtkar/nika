<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;

class IntelligencePointQuestion extends Pivot
{
    public function scopeWithGroupIntelligencePointId(Builder $builder)
    {
        $builder->selectRaw('SUM(max_point) AS max_point_sum,question_id,intelligence_point_id')
            ->groupBy('intelligence_point_id');
    }
}
