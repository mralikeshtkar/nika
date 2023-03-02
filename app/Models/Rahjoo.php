<?php

namespace App\Models;

use App\Enums\RahjooParent\RahjooParentGender;
use Awobaz\Compoships\Compoships;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Builder;
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
        'support_id',
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
            $model->code = IdGenerator::generate(['table' => (new self())->getTable(), 'field' => 'code', 'length' => 6, 'prefix' => rand(1, 9)]);
        });
    }

    #endregion

    #region Relations

    /**
     * @return BelongsTo
     */
    public function rahyab(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rahyab_id');
    }

    /**
     * @return BelongsToMany
     */
    public function intelligenceRahnama(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'rahjoo_intelligence_rahyab', 'rahjoo_id', 'rahnama_id', 'id', 'id')
            ->using(RahjooIntelligenceRahyab::class);
    }

    /**
     * @return HasMany
     */
    public function pivotIntelligenceRahnama(): HasMany
    {
        return $this->hasMany(RahjooIntelligenceRahyab::class, 'rahjoo_id');
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
     * @return BelongsTo
     */
    public function support(): BelongsTo
    {
        return $this->belongsTo(User::class, 'support_id');
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
    public function packageExercises(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->package(), (new Package())->exercises());
    }

    public function packageIntelligences(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->package(), (new Package())->intelligences());
    }

    /**
     * @return BelongsToMany
     */
    public function intelligencePackagePoints(): BelongsToMany
    {
        return $this->belongsToMany(IntelligencePackage::class, 'intelligence_package_point_rahjoo', 'rahjoo_id', 'intelligence_package_id', 'id', 'pivot_id')
            ->withPivot(['user_id', 'intelligence_point_id', 'point'])
            ->withTimestamps();
    }

    /**
     * @return HasMany
     */
    public function pivotIntelligencePackagePoints(): HasMany
    {
        return $this->hasMany(IntelligencePackagePointRahjoo::class, 'rahjoo_id');
    }

    /**
     * @return HasManyDeep
     */
    public function packagePivotIntelligencePackage(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->package(), (new Package())->pivotIntelligencePackage());
    }

    /**
     * @return HasManyDeep
     */
    public function packageIntelligencePackage(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->package(), (new Package())->intelligences());
    }

    /**
     * @return HasManyDeep
     */
    public function questions(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->package(), (new Package())->exercises(), (new Exercise())->questions());
    }

    public function questionDuration(): HasOne
    {
        return $this->hasOne(QuestionDuration::class, 'rahjoo_id');
    }

    public function questionAnswers(): HasMany
    {
        return $this->hasMany(QuestionAnswer::class, 'rahjoo_id');
    }

    public function lastExercise(): HasOne
    {
        return $this->hasOne(Exercise::class, 'id', 'last_exercise_id');
    }

    #endregion

    #region Scopes

    public function scopeLastExercise(Builder $builder, $hasIntelligencePackage = false)
    {
        $builder->addSelect([
            'last_exercise_id' => Exercise::query()
                ->select('id')
                ->when($hasIntelligencePackage,function ($q){
                    $q->whereHas('intelligencePackage', function ($q) {
                        $q->where('intelligence_package.package_id', 'rahjoos.package_id');
                    });
                })->whereHas('questions', function ($q) {
                    $q->whereHas('answerTypes', function ($q) {
                        $q->whereDoesntHave('answer', function ($q) {
                            $q->whereColumn('rahjoo_id', 'rahjoos.id');
                        });
                    });
                })->limit(1)
        ])->with('lastExercise:id,title');
    }

    #endregion
}
