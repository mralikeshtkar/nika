<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory, Compoships;

    #region Constance

    protected $fillable = [
        'user_id',
        'package_id',
        'intelligence_id',
        'title',
    ];

    #endregion
}
