<?php

namespace App\Models;

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
    ];

    protected $casts=[
        'is_percent'=>'bool',
    ];

    #region Relations

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    #endregion
}
