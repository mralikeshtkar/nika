<?php

namespace App\Models;

use App\Traits\Media\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Package extends Model
{
    use HasFactory, HasMedia;

    #region Constance

    const MEDIA_COLLECTION_VIDEO = "media collection video";

    const MEDIA_DIRECTORY_VIDEOS = "videos";

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'title',
        'status',
        'age',
        'price',
        'is_completed',
        'description',
    ];

    protected $casts = [
        'is_completed' => 'bool',
    ];

    #endregion

    #region Relations

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return MorphOne
     */
    public function video(): MorphOne
    {
        return $this->singleMedia()->where('collection', self::MEDIA_COLLECTION_VIDEO);
    }

    #endregion
}
