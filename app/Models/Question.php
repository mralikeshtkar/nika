<?php

namespace App\Models;

use App\Traits\Comment\HasComment;
use App\Traits\Media\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Staudenmeir\EloquentHasManyDeep\HasTableAlias;

class Question extends Model
{
    use HasFactory, HasMedia, HasRelationships, HasTableAlias,HasComment;

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
        'priority',
    ];

    #endregion

    #region Relations

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    /**
     * @return HasMany
     */
    public function files(): HasMany
    {
        return $this->hasMany(MediaQuestion::class, 'question_id')
            ->orderBy('priority');
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
     * @return HasMany
     */
    public function answers(): HasMany
    {
        return $this->hasMany(QuestionAnswer::class);
    }


    /**
     * @return HasManyThrough
     */
    public function answerRahjoos(): HasManyThrough
    {
        return $this->hasManyThrough(Rahjoo::class,QuestionAnswer::class,'question_id','id','id','rahjoo_id');
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
        return $this->hasOneDeep(Intelligence::class, [Exercise::class, IntelligencePackage::class], ['id', 'pivot_id', 'id'], ['exercise_id', 'intelligence_package_id', 'intelligence_id']);
    }

    /**
     * @return HasOneDeep
     */
    public function intelligencePackage(): HasOneDeep
    {
        return $this->hasOneDeep(IntelligencePackage::class, [Exercise::class], ['id', 'pivot_id'], ['exercise_id', 'intelligence_package_id']);
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
