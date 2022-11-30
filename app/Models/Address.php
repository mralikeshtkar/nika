<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Address extends Model
{
    use HasFactory, HasRelationships;

    #region Constance

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'city_id',
        'address',
        'postal_code',
        'phone_number',
        'type',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'city_id' => 'int',
    ];

    #endregion

    #region Methods

    /**
     * Load aggregate province name.
     *
     * @return Address
     */
    public function loadCityName(): Address
    {
        return $this->loadAggregate('city', 'name');
    }

    /**
     * Load aggregate province name.
     *
     * @return Address
     */
    public function loadProvinceName(): Address
    {
        return $this->loadAggregate('province AS province_name', 'provinces.name');
    }

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
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * @return HasOneDeep
     */
    public function province(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->city(), (new City())->province());
    }

    #endregion

    #region Scopes

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeWithCityName(Builder $builder)
    {
        $builder->withAggregate('city', 'name');
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeWithProvinceName(Builder $builder)
    {
        $builder->withAggregate('province AS province_name', 'provinces.name');
    }

    #endregion

}
