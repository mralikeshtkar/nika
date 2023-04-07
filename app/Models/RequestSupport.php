<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestSupport extends Model
{
    protected $fillable = [
        'user_id',
        'conformer_id',
    ];

    #region Relations

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
    public function conformer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'conformer_id');
    }

    #endregion
}
