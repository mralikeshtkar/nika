<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Intelligence extends Model
{
    use HasFactory;

    #region Constance

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'title',
        'is_completed',
    ];

    protected $casts = [
        'is_completed' => 'bool',
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
     * @return BelongsToMany
     */
    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class);
    }

    /**
     * @return HasMany
     */
    public function points(): HasMany
    {
        return $this->hasMany(IntelligencePoint::class);
    }

    #endregion
}
