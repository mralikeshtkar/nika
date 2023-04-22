<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Discount extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'is_percent',
        'amount',
        'enable_at',
        'expire_at',
        'usage_limitation',
        'status',
    ];

    #region Relations

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    #endregion
}
