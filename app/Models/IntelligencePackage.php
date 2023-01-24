<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Awobaz\Compoships\Database\Eloquent\Relations\BelongsTo;
use Awobaz\Compoships\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class IntelligencePackage extends Pivot
{
    use Compoships;

    /**
     * @var string[]
     */
    protected $casts = [
        'is_completed' => 'bool',
    ];

    /**
     * @return HasMany
     */
    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class, 'intelligence_package_id','pivot_id');
    }

    public function intelligence(): BelongsTo
    {
        return $this->belongsTo(Intelligence::class);
    }

    /**
     * @return HasMany
     */
    public function points(): HasMany
    {
        return $this->hasMany(IntelligencePoint::class, 'intelligence_package_id', 'pivot_id');
    }

    /**
     * @return HasMany
     */
    public function feedbacks(): HasMany
    {
        return $this->hasMany(IntelligenceFeedback::class, 'intelligence_package_id', 'pivot_id');
    }
}
