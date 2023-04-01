<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RahjooSupport extends Model
{
    protected $fillable = [
        'support_id',
        'rahjoo_id',
        'step',
        'pay_url',
        'pay_url_generated_at',
    ];

    protected $casts = [
        'pay_url_generated_at' => 'datetime',
    ];

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(SupportComment::class,'rahjoo_support_id','id');
    }

    /**
     * @return BelongsTo
     */
    public function rahjoo(): BelongsTo
    {
        return $this->belongsTo(Rahjoo::class,'rahjoo_id','id');
    }

    /**
     * @return BelongsTo
     */
    public function support(): BelongsTo
    {
        return $this->belongsTo(User::class,'support_id','id');
    }
}
