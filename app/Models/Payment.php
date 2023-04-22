<?php

namespace App\Models;

use App\Enums\Payment\PaymentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payment extends Model
{
    protected $fillable = [
        'owner_id',
        'rahjoo_support_id',
        'product_id',
        'action',
        'paymentable_id',
        'paymentable_type',
        'invoice_id',
        'referenceId',
        'amount',
        'gateway',
        'status',
        'date',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    #region Methods

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return PaymentStatus::Success == $this->status;
    }

    #endregion

    #region Relations

    /**
     * @return MorphTo
     */
    public function paymentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function rahjooSupport(): BelongsTo
    {
        return $this->belongsTo(RahjooSupport::class, 'rahjoo_support_id');
    }

    #endregion

    #region Scopes

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeSuccess(Builder $builder)
    {
        $builder->where('status', PaymentStatus::Success)
            ->whereNotNull('date')
            ->whereNotNull('referenceId');
    }

    public function scopePending(Builder $builder)
    {
        $builder->where('status', PaymentStatus::Pending);
    }

    #endregion
}
