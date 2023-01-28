<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'media_id',
        'text',
        'priority',
    ];

    #region Methods

    protected static function boot()
    {
        parent::boot();
        static::deleted(function () {
            dd(func_get_args());
        });
    }

    #endregion
}
