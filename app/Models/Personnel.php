<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Personnel extends Model
{
    use HasFactory;

    #region Constance

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'major_id',
        'job_id',
        'birth_certificate_place_id',
        'is_married',
        'birth_certificate_number',
        'email',
        'language_level',
        'computer_level',
        'research_history',
        'is_working',
        'work_description',
    ];

    protected $casts = [
        'user_id' => 'int',
        'major_id' => 'int',
        'birth_certificate_place_id' => 'int',
        'job_id' => 'int',
        'is_married' => 'boolean',
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

    #endregion
}
