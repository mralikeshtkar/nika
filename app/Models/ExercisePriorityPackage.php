<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ExercisePriorityPackage extends Pivot
{
    use Compoships;

    /**
     * @return BelongsTo
     */
    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    /**
     * @return BelongsTo
     */
    public function intelligence(): BelongsTo
    {
        return $this->belongsTo(Intelligence::class);
    }
}
