<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RahjooCourse extends Model
{
    use HasFactory;

    #region Constance

    /**
     * @var string[]
     */
    protected $fillable=[
        'user_id',
        'rahjoo_id',
        'name',
        'duration',
    ];

    protected $casts=[
        'duration'=>'int',
    ];

    #endregion

    #region Relations

    /**
     * @return BelongsTo
     */
    public function rahjoo(): BelongsTo
    {
        return $this->belongsTo(Rahjoo::class);
    }

    #endregion
}
