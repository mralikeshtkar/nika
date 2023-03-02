<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Awobaz\Compoships\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Exercise extends Model
{
    use HasFactory, Compoships, HasRelationships;

    #region Constance

    protected $fillable = [
        'user_id',
        'intelligence_package_id',
        'title',
        'is_locked',
    ];

    protected $casts = [
        'is_locked' => 'bool',
        'latest_answer_at' => 'datetime',
    ];

    #endregion

    #region Relations

    /**
     * @return BelongsTo
     */
    public function intelligencePackage(): BelongsTo
    {
        return $this->belongsTo(IntelligencePackage::class, 'intelligence_package_id', 'pivot_id');
    }

    /**
     * @return HasManyDeep
     */
    public function intelligence(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->intelligencePackage(), (new IntelligencePackage())->intelligence());
    }

    /**
     * @return HasManyDeep
     */
    public function package(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->intelligencePackage(), (new IntelligencePackage())->package());
    }

    /**
     * @return HasMany
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)
            ->orderBy('priority');
    }

    /**
     * @return HasManyDeep
     */
    public function questionAnswerTypes(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->questions(), (new Question())->answerTypes());
    }

    public function questionAnswers(): HasManyThrough
    {
        return $this->hasManyThrough(QuestionAnswer::class, Question::class, 'exercise_id', 'question_id', 'id', 'id');
    }

    public function questionAnswer(): HasManyThrough
    {
        return $this->hasOneThrough(QuestionAnswer::class, Question::class, 'exercise_id', 'question_id', 'id', 'id')
            ->latest();
    }

    /**
     * @return HasManyDeep
     */
    public function points(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->questions(), (new Question())->points())
            ->withPivot((new IntelligencePointQuestion)->getTable());
    }

    /**
     * @return HasManyDeep
     */
    public function pivotPoints(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->questions(), (new Question())->pivotPoints());
    }

    #endregion

    #region Scopes

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeLocked(Builder $builder)
    {
        $builder->where('is_locked', true);
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeNotLocked(Builder $builder)
    {
        $builder->where('is_locked', false);
    }

    public function scopeWithQuestionAnswersCount(Builder $builder, $rahjoo_id)
    {
        $builder->addSelect([
            'question_answers_count' => Question::query()->selectRaw('COUNT(distinct question_answers.question_id)')
                ->whereColumn('questions.exercise_id', 'exercises.id')
                ->join('question_answers', 'question_answers.question_id', '=', 'questions.id')
                ->where('question_answers.rahjoo_id', $rahjoo_id)
        ]);
    }

    #endregion


}
