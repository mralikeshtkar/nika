<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    const POLY_MORPHIC_KEY = "commentable";

    protected $fillable = [
        'user_id',
        'commentable_id',
        'commentable_type',
        'body',
    ];

    #region Relation

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    #endregion
}
