<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Awobaz\Compoships\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class IntelligencePackage extends Pivot
{
    use Compoships;

    protected $with = ['exercises'];

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
        return $this->hasMany(Exercise::class, ['intelligence_id', 'package_id'], ['intelligence_id', 'package_id']);
    }
}
