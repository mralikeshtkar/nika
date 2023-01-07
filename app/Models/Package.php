<?php

namespace App\Models;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use App\Enums\Package\PackageStatus;
use App\Traits\Media\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Package extends Model
{
    use HasFactory, HasMedia, EagerLoadPivotTrait;

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
     * @return HasMany
     */
    public function pivotIntelligences(): HasMany
    {
        return $this->hasMany(IntelligencePackage::class);
    }

    #endregion
}
