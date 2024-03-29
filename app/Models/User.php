<?php

namespace App\Models;

use App\Enums\Role as RoleEnum;
use App\Enums\UserStatus;
use App\Traits\Media\HasMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasRelationships, HasMedia;

    #region Constance

    const MEDIA_COLLECTION_PROFILES = "media collection profiles";

    const MEDIA_DIRECTORY_PROFILES = "profiles";

    const BIRTHDATE_VALIDATION_FORMAT = 'Y/m/d';

    protected $guard_name = '*';

    public const AUTHENTICATION_TOKEN_KEY = "Authentication token";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'background',
        'color',
        'city_id',
        'grade_id',
        'birth_place_id',
        'first_name',
        'last_name',
        'father_name',
        'mobile',
        'national_code',
        'ip',
        'birthdate',
        'password',
        'verification_code',
        'verification_code_expired_at',
        'verified_at',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verification_code' => 'int',
        'city_id' => 'int',
        'grade_id' => 'int',
        'birth_place_id' => 'int',
        'birthdate' => 'datetime',
        'verification_code_expired_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    #endregion

    #region Methods

    public function calculateAge($birthdate): int
    {
        return Carbon::now()->diff($birthdate)->y;
    }

    /**
     * Generate new api token.
     *
     * @return string
     */
    public function generateToken(): string
    {
        //todo remove all previous login
        //$this->tokens()->delete();
        return $this->createToken(self::AUTHENTICATION_TOKEN_KEY)->plainTextToken;
    }

    /**
     * Check user account is inactive.
     *
     * @return bool
     */
    public function isInactive(): bool
    {
        return UserStatus::Inactive()->is($this->status);
    }

    /**
     * Check user a personnel or super admin.
     *
     * @return bool
     */
    public function isPersonnel(): bool
    {
        return $this->hasAnyRole(Arr::except(\App\Enums\Role::asArray(), \App\Enums\Role::RAHJOO()->key));
    }

    public function isRahjoo(): bool
    {
        return $this->isSuperAdmin() || $this->hasRole(RoleEnum::RAHJOO);
    }

    public function isRahyab(): bool
    {
        return $this->isSuperAdmin() || $this->hasRahyabRole();
    }

    public function hasRahyabRole(): bool
    {
        return $this->hasRole(RoleEnum::RAHYAB);
    }

    public function isRahnama(): bool
    {
        return $this->isSuperAdmin() || $this->hasRahnamaRole();
    }

    public function hasRahnamaRole(): bool
    {
        return $this->hasRole(RoleEnum::RAHNAMA);
    }

    public function isSupport(): bool
    {
        return $this->isSuperAdmin() || $this->hasSupportRole();
    }

    public function hasSupportRole(): bool
    {
        return $this->hasRole(RoleEnum::SUPPORT);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(RoleEnum::SUPER_ADMIN);
    }

    /**
     * Check verification code is expired or not.
     *
     * @return bool
     */
    public function verificationCodeIsExpired(): bool
    {
        return is_null($this->verification_code_expired_at) || $this->verification_code_expired_at->lt(now());
    }

    /**
     * Check verification code is equal to parameter.
     *
     * @param $verification_code
     * @return bool
     */
    public function checkVerificationCode($verification_code): bool
    {
        return $this->verification_code == $verification_code;
    }

    /**
     * Check password is not null or empty.
     *
     * @return bool
     */
    public function hasPassword(): bool
    {
        return boolval($this->password);
    }

    #endregion

    #region Relations

    /**
     * @return BelongsToMany
     */
    public function rahnamaIntelligences(): BelongsToMany
    {
        return $this->belongsToMany(Intelligence::class, 'intelligence_rahnama', 'rahnama_id', 'intelligence_id', 'id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function birth_place(): BelongsTo
    {
        return $this->belongsTo(City::class, 'birth_place_id');
    }

    /**
     * @return HasOneThrough
     */
    public function birth_place_province(): HasOneThrough
    {
        return $this->hasOneThrough(Province::class, City::class, 'id', 'id', 'birth_place_id', 'province_id');
    }

    /**
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /**
     * @return HasOneThrough
     */
    public function province(): HasOneThrough
    {
        return $this->hasOneThrough(Province::class, City::class, 'id', 'id', 'city_id', 'province_id');
    }

    /**
     * @return BelongsTo
     */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    /**
     * @return HasOne
     */
    public function personnel(): HasOne
    {
        return $this->hasOne(Personnel::class);
    }

    /**
     * @return HasOne
     */
    public function rahjoo(): HasOne
    {
        return $this->hasOne(Rahjoo::class);
    }

    /**
     * @return BelongsToMany
     */
    public function intelligences(): BelongsToMany
    {
        return $this->belongsToMany(Intelligence::class);
    }

    /**
     * @return MorphOne
     */
    public function profile(): MorphOne
    {
        return $this->singleMedia()->where('collection', self::MEDIA_COLLECTION_PROFILES);
    }

    /**
     * @return HasMany
     */
    public function pivotRahjooIntelligences(): HasMany
    {
        return $this->hasMany(RahjooIntelligenceRahyab::class, 'rahnama_id');
    }

    /**
     * @return HasMany
     */
    public function rahnamaRahjoos(): HasMany
    {
        return $this->hasMany(Rahjoo::class, 'rahnama_id');
    }

    public function rahnamaRahjooQuestions()
    {
        return $this->hasManyDeepFromRelations($this->rahnamaRahjoos(), (new Rahjoo())->questions());
    }

    /**
     * @return HasMany
     */
    public function rahyabRahjoos(): HasMany
    {
        return $this->hasMany(Rahjoo::class, 'rahyab_id');
    }

    #endregion

    #region Scopes

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeVerified(Builder $builder)
    {
        $builder->whereNotNull('verified_at');
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeUnverified(Builder $builder)
    {
        $builder->whereNull('verified_at');
    }

    #endregion
}
