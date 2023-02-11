<?php

namespace App\Models;

use App\Enums\RahjooParent\RahjooParentGender;
use Awobaz\Compoships\Compoships;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Rahjoo extends Model
{
    use HasFactory, Compoships, HasRelationships;

    #region Constance

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'rahnama_id',
        'rahyab_id',
        'agent_id',
        'package_id',
        'code',
        'school',
        'which_child_of_family',
        'disease_background',
    ];

    #endregion

    #region Methods

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->code = IdGenerator::generate(['table' => self::getTable(), 'field' => 'code', 'length' => 6]);
        });
    }

    #endregion

    #region Relations

    /**
     * @return BelongsToMany
     */
    public function intelligenceRahyab(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'rahjoo_intelligence_rahyab', 'rahjoo_id', 'rahnama_id', 'id', 'id')
            ->using(RahjooIntelligenceRahyab::class);
    }

    /**
     * @return HasMany
     */
    public function pivotIntelligenceRahyab(): HasMany
    {
        return $this->hasMany(RahjooIntelligenceRahyab::class,'rahjoo_id');
    }

    public function questionPoints(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'question_point_rahjoo')
            ->using(QuestionPointRahjoo::class)
            ->withPivot(['point'])
            ->withTimestamps();
    }

    /**
     * @return HasMany
     */
    public function pivotQuestionPoints(): HasMany
    {
        return $this->hasMany(QuestionPointRahjoo::class, 'rahjoo_id');
    }

    /**
     * @return \Awobaz\Compoships\Database\Eloquent\Relations\HasMany
     */
    public function answers(): \Awobaz\Compoships\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(QuestionAnswer::class, ['rahjoo_id', 'question_id'], ['id', 'laravel_through_key']);
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
    public function rahnama(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rahnama_id');
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

    /**
     * @return HasManyDeep
     */
    public function questions(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->package(), (new Package())->exercises(), (new Exercise())->questions());
    }

    #endregion
}
