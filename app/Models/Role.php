<?php

namespace App\Models;

use App\Enums\Role as RoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory;

    #region Constance

    protected string $guard_name = "*";

    protected $casts = [
        'is_locked' => 'bool',
    ];

    const ROLES = [
        ['name' => RoleEnum::SUPER_ADMIN, 'is_locked' => true],
        ['name' => RoleEnum::PERSONNEL, 'is_locked' => true],
        ['name' => RoleEnum::RAHJOO, 'is_locked' => true],
        ['name' => RoleEnum::RAHNAMA, 'is_locked' => true],
        ['name' => RoleEnum::RAHYAB, 'is_locked' => true],
        ['name' => RoleEnum::SUPPORT, 'is_locked' => true],
        ['name' => RoleEnum::STOREKEEPER, 'is_locked' => true],
        ['name' => RoleEnum::AGENT, 'is_locked' => true],
    ];

    #endregion

    #region Methods

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $attributes['guard_name'] = $this->guard_name;
        parent::__construct($attributes);
    }

    /**
     * Check role is locked.
     *
     * @return mixed
     */
    public function isLocked(): mixed
    {
        return $this->is_locked;
    }

    #endregion
}
