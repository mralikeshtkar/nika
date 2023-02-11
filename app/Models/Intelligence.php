<?php

namespace App\Models;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Intelligence extends Model
{
    use HasFactory, Compoships, EagerLoadPivotTrait;

    #region Constance

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'title',
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
     * @return BelongsToMany
     */
    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class)
            ->using(IntelligencePackage::class)
            ->withPivot(['pivot_id', 'is_completed']);
    }

    /**
     * @return HasMany
     */
    public function pivotPackages(): HasMany
    {
        return $this->hasMany(IntelligencePackage::class);
    }

    /**
     * @return BelongsToMany
     */
    public function rahnama(): BelongsToMany
    {
        return $this->belongsToMany(Intelligence::class, 'intelligence_rahnama', 'intelligence_id', 'rahnama_id', 'id', 'id');
    }

    #endregion
}
