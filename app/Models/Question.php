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
use Illuminate\Database\Eloquent\Relations\HasOne;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Staudenmeir\EloquentHasManyDeep\HasTableAlias;

class Question extends Model
{
    use HasFactory, HasMedia, HasRelationships, HasTableAlias, HasComment;

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

    protected $casts = [
        'is_answered' => 'bool',
        'latest_answer_created_at' => 'datetime',
        'question_duration_start_start' => 'datetime',
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
     * @return HasOne
     */
    public function latestAnswer(): HasOne
    {
        return $this->hasOne(QuestionAnswer::class)->latest();
    }


    /**
     * @return HasManyThrough
     */
    public function answerRahjoos(): HasManyThrough
    {
        return $this->hasManyThrough(Rahjoo::class, QuestionAnswer::class, 'question_id', 'id', 'id', 'rahjoo_id')->distinct();
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
    public function pivotRahjooPoints(): HasMany
    {
        return $this->hasMany(QuestionPointRahjoo::class, 'question_id');
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
    public function pivotExerciseIntelligencePackage(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->exercise(),(new Exercise())->intelligencePackage());
    }

    /**
     * @return HasOneDeep
     */
    public function intelligencePackage(): HasOneDeep
    {
        return $this->hasOneDeep(IntelligencePackage::class, [Exercise::class], ['id', 'pivot_id'], ['exercise_id', 'intelligence_package_id']);
    }

    /**
     * @return HasOneDeep
     */
    public function package(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->intelligencePackage(), (new IntelligencePackage())->package());
    }

    /**
     * @return HasManyDeep
     */
    public function exercisePivotPoints(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->exercise(), (new Exercise())->pivotPoints());
    }

    public function questionDurationStart(): HasOne
    {
        return $this->hasOne(QuestionDuration::class);
    }

    #endregion
}
