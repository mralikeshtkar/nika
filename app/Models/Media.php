<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    #region Constance

    const MEDIA_PUBLIC_DISK = "public";

    const MEDIA_PRIVATE_DISK = "private";

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'model',
        'disk',
        'files',
        'extension',
        'type',
        'collection',
        'base_url',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'files' => 'array'
    ];

    #endregion

    #region Methods

    /**
     * @return bool
     */
    public function isDiskPrivate(): bool
    {
        return $this->disk == self::MEDIA_PRIVATE_DISK;
    }

    #endregion
}
