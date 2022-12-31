<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MediaQuestion extends Pivot
{
    protected static function boot()
    {
        parent::boot();
        static::deleted(function (){
            dd(func_get_args());
        });
    }
}
