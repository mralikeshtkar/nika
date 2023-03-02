<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RahjooSupport extends Model
{
    protected $fillable = [
        'support_id',
        'rahjoo_id',
        'pay_url',
        'pay_url_generated_at',
    ];

    protected $casts = [
        'pay_url_generated_at' => 'datetime',
    ];
}
