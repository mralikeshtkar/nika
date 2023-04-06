<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payment extends Model
{
    protected $fillable = [
        'owner_id',
        'rahjoo_support_id',
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

    #region Relations

    /**
     * @return MorphTo
     */
    public function paymentable(): MorphTo
    {
        return $this->morphTo();
    }

    #endregion
}
