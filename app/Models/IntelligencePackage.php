<?php

namespace App\Models;

use App\Traits\Comment\HasComment;
use Awobaz\Compoships\Compoships;
use Awobaz\Compoships\Database\Eloquent\Relations\BelongsTo;
use Awobaz\Compoships\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class IntelligencePackage extends Pivot
{
    use Compoships, HasRelationships, HasComment;

    /**
     * @var string[]
     */
    protected $casts = [
        'is_completed' => 'bool',
    ];

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, Comment::POLY_MORPHIC_KEY, null, null, 'pivot_id');
    }


    /**
     * @return HasMany
     */
    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class, 'intelligence_package_id', 'pivot_id');
    }

    /**
     * @return HasOne
     */
    public function exercise(): HasOne
    {
        return $this->hasOne(Exercise::class, 'intelligence_package_id', 'pivot_id');
    }

    /**
     * @return HasManyDeep
     */
    public function exerciseQuestionPivotPoints(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->exercises(), (new Exercise())->questions(), (new Question())->pivotPoints());
    }

    public function intelligence(): BelongsTo
    {
        return $this->belongsTo(Intelligence::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
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

    /**
     * @return HasMany
     */
    public function pivotIntelligenceRahjooRahyab(): HasMany
    {
        return $this->hasMany(RahjooIntelligenceRahyab::class,'intelligence_id', 'intelligence_id');
    }
}
