<?php

namespace App\Models;

use App\Enums\Discount\DiscountStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Discount extends Model
{
    const ENABLE_AT_VALIDATION_FORMAT = 'Y/m/d H:i:s';
    const EXPIRE_AT_VALIDATION_FORMAT = 'Y/m/d H:i:s';

    protected $fillable = [
        'user_id',
        'code',
        'is_percent',
        'amount',
        'enable_at',
        'expire_at',
        'usage_limitation',
        'status',
        'discount',
        'price',
    ];

    protected $casts = [
        'is_percent' => 'bool',
        'discount' => 'array',
    ];

    #region Methods

    public function calculateFinalPrice($price)
    {
        if ($this->is_percent) {
            return $price - (($price / 100) * $this->amount);
        } else {
            return $this->amount > $price ? 0 : $price - $this->amount;
        }
    }

    #endregion

    #region Relations

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    #endregion

    #region Scopes

    public function scopeActive(Builder $builder)
    {
        $builder->where('status', DiscountStatus::Active);
    }

    #endregion
}
