<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\Pivot;

class QuestionPointRahjoo extends Pivot
{
    protected $table='question_point_rahjoo';

    /**
     * @return HasOneThrough
     */
    public function intelligencePointName(): HasOneThrough
    {
        return $this->hasOneThrough(IntelligencePointName::class,IntelligencePoint::class,'id','id','intelligence_point_id','intelligence_point_name_id');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
