<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Order extends Model
{
    use HasRelationships;

    const SENT_AT_VALIDATION_FORMAT = 'Y/m/d';

    protected $fillable = [
        'rahjoo_id',
        'rahjoo_support_id',
        'payment_id',
        'code',
        'tracking_code',
        'sent_at',
        'is_used',
        'status',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'sent_at' => 'datetime',
    ];

    #region Relations

    /**
     * @return BelongsTo
     */
    public function rahjoo(): BelongsTo
    {
        return $this->belongsTo(Rahjoo::class,'rahjoo_id');
    }

    /**
     * @return BelongsTo
     */
    public function rahjooSupport(): BelongsTo
    {
        return $this->belongsTo(RahjooSupport::class, 'rahjoo_support_id');
    }

    /**
     * @return BelongsTo
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function rahjooUser(): HasOneThrough
    {
        return $this->hasOneThrough(User::class,Rahjoo::class,'id','id','rahjoo_id','user_id');
    }

    #endregion

    #region Scopes

    /**
     * @param $q
     * @return void
     */
    public function scopeNotUsed($q)
    {
        $q->where('is_used', false);
    }

    #endregion
}
