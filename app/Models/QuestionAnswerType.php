<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class QuestionAnswerType extends Model
{
    use HasFactory;

    #region Constance

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'question_id',
        'type',
        'priority',
    ];

    #endregion

    #region Relations

    /**
     * @return HasOne
     */
    public function answer(): HasOne
    {
        return $this->hasOne(QuestionAnswer::class, 'question_answer_type_id');
    }

    /**
     * @return HasMany
     */
    public function answers(): HasMany
    {
        return $this->hasMany(QuestionAnswer::class, 'question_answer_type_id');
    }

    #endregion
}
