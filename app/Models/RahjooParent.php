<?php

namespace App\Models;

use App\Enums\RahjooParent\RahjooParentGender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RahjooParent extends Model
{
    use HasFactory;

    #region Constance

    const BIRTHDATE_VALIDATION_FORMAT = 'Y/m/d';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'rahjoo_id',
        'job_id',
        'grade_id',
        'major_id',
        'name',
        'mobile',
        'gender',
        'birthdate',
        'child_count',
    ];

    #endregion

    #region Methods

    /**
     * @return string
     */
    public function getTranslatedGender(): string
    {
        return RahjooParentGender::getDescription($this->gender);
    }

    #endregion
}
