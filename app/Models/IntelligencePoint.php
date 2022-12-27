<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntelligencePoint extends Model
{
    use HasFactory;

    #region Constance

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'intelligence_id',
        'intelligence_point_name_id',
        'max_point',
    ];

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
     * @return BelongsTo
     */
    public function intelligence(): BelongsTo
    {
        return $this->belongsTo(Intelligence::class);
    }

    /**
     * @return BelongsTo
     */
    public function intelligencePointName(): BelongsTo
    {
        return $this->belongsTo(IntelligencePointName::class);
    }

    #endregion
}
