<?php

namespace App\Models;

use App\Enums\RahjooParent\RahjooParentGender;
use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Rahjoo extends Model
{
    use HasFactory,Compoships;

    #region Constance

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'agent_id',
        'package_id',
        'school',
        'which_child_of_family',
        'disease_background',
    ];

    #endregion

    #region Relations

    /**
     * @return \Awobaz\Compoships\Database\Eloquent\Relations\HasMany
     */
    public function answers(): \Awobaz\Compoships\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(QuestionAnswer::class,['rahjoo_id','question_id'],['id','laravel_through_key']);
    }

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
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * @return HasMany
     */
    public function parents(): HasMany
    {
        return $this->hasMany(RahjooParent::class);
    }

    /**
     * @return HasOne
     */
    public function father(): HasOne
    {
        return $this->hasOne(RahjooParent::class)->where('gender', RahjooParentGender::Male);
    }

    /**
     * @return HasOne
     */
    public function mother(): HasOne
    {
        return $this->hasOne(RahjooParent::class)->where('gender', RahjooParentGender::Female);
    }

    /**
     * @return HasMany
     */
    public function courses(): HasMany
    {
        return $this->hasMany(RahjooCourse::class);
    }

    /**
     * @return BelongsTo
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    #endregion
}
