<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PsychologicalQuestion extends Model
{
    use HasFactory;

    #region Constance

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'rahjoo_id',
        'favourite_job_id',
        'parent_favourite_job_id',
        'negative_positive_points',
        'favourites',
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
     * @return BelongsTo
     */
    public function rahjoo(): BelongsTo
    {
        return $this->belongsTo(Rahjoo::class);
    }

    /**
     * @return BelongsTo
     */
    public function favourite_job(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'favourite_job_id');
    }

    /**
     * @return BelongsTo
     */
    public function parent_favourite_job(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'parent_favourite_job_id');
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class);
    }

    #endregion
}
