<?php

namespace App\Models;

use App\Traits\Media\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Staudenmeir\EloquentHasManyDeep\HasTableAlias;

class Question extends Model
{
    use HasFactory, HasMedia, HasRelationships, HasTableAlias;

    #region Constance

    const MEDIA_COLLECTION_FILES = "files";

    const MEDIA_COLLECTION_QUESTIONS = "questions";

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'exercise_id',
        'title',
    ];

    #endregion

    #region Relations

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    /**
     * @return HasManyDeep
     */
    public function intelligencePoints(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->intelligence(),(new Intelligence)->points());
    }

    /**
     * @return BelongsToMany
     */
    public function files(): BelongsToMany
    {
        return $this->belongsToMany(Media::class)
            ->using(MediaQuestion::class)
            ->withPivot(['priority'])
            ->orderByPivot('priority');
    }

    /**
     * @return HasMany
     */
    public function pivotMedia(): HasMany
    {
        return $this->hasMany(MediaQuestion::class);
    }

    /**
     * @return HasMany
     */
    public function answerTypes(): HasMany
    {
        return $this->hasMany(QuestionAnswerType::class)
            ->orderBy('priority');
    }

    /**
     * @return BelongsToMany
     */
    public function points(): BelongsToMany
    {
        return $this->belongsToMany(IntelligencePoint::class)
            ->using(IntelligencePointQuestion::class)
            ->withPivot(['max_point', 'description']);
    }

    /**
     * @return HasMany
     */
    public function pivotPoints(): HasMany
    {
        return $this->hasMany(IntelligencePointQuestion::class);
    }

    /**
     * @return HasOneDeep
     */
    public function intelligence(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->exercise(), (new Exercise())->intelligence());
    }

    /**
     * @return HasManyDeep
     */
    public function exercisePivotPoints(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->exercise(), (new Exercise())->pivotPoints());
    }

    #endregion
}
