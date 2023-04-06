<?php

namespace App\Models;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use App\Enums\Package\PackageStatus;
use App\Traits\Media\HasMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Package extends Model
{
    use HasFactory, HasMedia, EagerLoadPivotTrait, HasRelationships;

    #region Constance

    const MEDIA_COLLECTION_VIDEO = "media collection video";

    const MEDIA_DIRECTORY_VIDEOS = "videos";

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'title',
        'status',
        'age',
        'price',
        'quantity',
        'is_completed',
        'description',
    ];

    protected $casts = [
        'is_completed' => 'bool',
    ];

    #endregion

    #region Methods

    /**
     * @return string
     */
    public function getTranslatedStatus(): string
    {
        return PackageStatus::getDescription($this->status);
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return PackageStatus::fromValue(intval($this->staus))->is(PackageStatus::Active);
    }

    /**
     * @return bool
     */
    public function isInactive(): bool
    {
        return PackageStatus::fromValue(intval($this->staus))->is(PackageStatus::Inactive);
    }

    /**
     * @return bool
     */
    public function hasQuantity(): bool
    {
        return $this->quantity > 0;
    }

    #endregion

    #region Relations

    /**
     * @return MorphMany
     */
    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return MorphOne
     */
    public function video(): MorphOne
    {
        return $this->singleMedia()->where('collection', self::MEDIA_COLLECTION_VIDEO);
    }

    /**
     * @return BelongsToMany
     */
    public function intelligences(): BelongsToMany
    {
        return $this->belongsToMany(Intelligence::class)
            ->using(IntelligencePackage::class)
            ->withPivot(['pivot_id', 'is_completed']);
    }

    /**
     * @return HasOneDeep
     */
    public function exercises(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->pivotIntelligences(), (new IntelligencePackage)->exercises());
    }

    /**
     * @return HasOneDeep
     */
    public function questions(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->pivotIntelligences(), (new IntelligencePackage)->exercises(), (new Exercise())->questions());
    }

    /**
     * @return HasMany
     */
    public function pivotIntelligences(): HasMany
    {
        return $this->hasMany(IntelligencePackage::class);
    }

    /**
     * @return BelongsToMany
     */
    public function exercisePriority(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'exercise_priority_package')
            ->orderBy('priority');
    }

    public function pivotExercisePriority(): HasMany
    {
        return $this->hasMany(ExercisePriorityPackage::class)
            ->orderBy('priority');
    }

    /**
     * @return HasMany
     */
    public function pivotIntelligencePackage(): HasMany
    {
        return $this->hasMany(IntelligencePackage::class);
    }

    /**
     * @return HasManyDeep
     */
    public function IntelligencePackageExercises(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->pivotIntelligencePackage(), (new IntelligencePackage)->exercises());
    }

    /**
     * @return HasMany
     */
    public function rahjoos(): HasMany
    {
        return $this->hasMany(Rahjoo::class);
    }

    #endregion
}
