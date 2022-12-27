<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class IntelligencePackage extends Pivot
{
    /**
     * @var string[]
     */
    protected $casts = [
        'is_completed' => 'bool',
    ];
}
