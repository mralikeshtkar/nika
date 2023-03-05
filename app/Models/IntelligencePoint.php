<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class IntelligencePoint extends Model
{
    use HasFactory;

    #region Constance

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'intelligence_package_id',
        'intelligence_point_name_id',
        'max_point',
    ];

    #endregion

    #region Relations

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function packageIntelligence(): BelongsTo
    {
        return $this->belongsTo(IntelligencePackage::class, 'intelligence_package_id', 'pivot_id');
    }

    /**
     * @return BelongsTo
     */
    public function intelligencePointName(): BelongsTo
    {
        return $this->belongsTo(IntelligencePointName::class);
    }

    /**
     * @return HasMany
     */
    public function questionPointRahjoo(): HasMany
    {
        return $this->hasMany(QuestionPointRahjoo::class);
    }

    /**
     * @return HasOne
     */
    public function pivotQuestionPointRahjoo(): HasOne
    {
        return $this->hasOne(QuestionPointRahjoo::class,'intelligence_point_id');
    }

    #endregion

    #region Scopes

    public function scopeWithPointName(Builder $builder)
    {
        $builder->withAggregate('intelligencePointName AS point_name', 'name');
    }

    #endregion
}
