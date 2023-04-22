<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    const SENT_AT_VALIDATION_FORMAT = 'Y/m/d H:i:s';

    protected $fillable = [
        'rahjoo_id',
        'rahjoo_support_id',
        'payment_id',
        'code',
        'tracking_code',
        'sent_at',
        'status',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    #region Relations

    /**
     * @return BelongsTo
     */
    public function rahjoo(): BelongsTo
    {
        return $this->belongsTo(Rahjoo::class);
    }

    /**
     * @return BelongsTo
     */
    public function rahjooSupport(): BelongsTo
    {
        return $this->belongsTo(RahjooSupport::class,'rahjoo_support_id');
    }

    /**
     * @return BelongsTo
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    #endregion
}
