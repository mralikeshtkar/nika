<?php

namespace App\Models;

use App\Traits\Media\HasMedia;
use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class QuestionAnswer extends Model
{
    use HasFactory, HasMedia,Compoships;

    protected $fillable = [
        'rahjoo_id',
        'question_id',
        'question_answer_type_id',
        'text',
    ];

    const MEDIA_DIRECTORY_QUESTION_ANSWERS = "question-answers";

    const MEDIA_COLLECTION_QUESTION_ANSWERS = "question-answers";

    #region Relations

    /**
     * @return BelongsTo
     */
    public function rahjoo(): BelongsTo
    {
        return $this->belongsTo(Rahjoo::class, 'rahjoo_id');
    }

    /**
     * @return BelongsTo
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'rahjoo_id');
    }

    /**
     * @return MorphOne
     */
    public function file(): MorphOne
    {
        return $this->singleMedia()->where('collection', self::MEDIA_COLLECTION_QUESTION_ANSWERS);
    }

    #endregion
}
