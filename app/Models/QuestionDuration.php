<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionDuration extends Model
{
    protected $fillable = [
        'rahjoo_id',
        'question_id',
        'start',
        'end',
    ];

    protected $casts=[
        'start'=>'datetime',
        'end'=>'datetime',
    ];

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
        return $this->belongsTo(Question::class);
    }

    #endregion
}
