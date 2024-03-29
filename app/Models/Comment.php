<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    const POLY_MORPHIC_KEY = "commentable";

    protected $fillable = [
        'user_id',
        'rahjoo_id',
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

    /**
     * @return BelongsTo
     */
    public function rahjoo(): BelongsTo
    {
        return $this->belongsTo(Rahjoo::class,'rahjoo_id');
    }

    #endregion

    #region Scopes

    /**
     * @param Builder $builder
     * @param $rahjoo_id
     * @return void
     */
    public function scopeWhereRahjoo(Builder $builder, $rahjoo_id)
    {
        $builder->where('rahjoo_id',$rahjoo_id);
    }

    #endregion
}
