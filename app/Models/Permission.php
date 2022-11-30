<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends \Spatie\Permission\Models\Permission
{
    use HasFactory;

    #region Constance

    protected string $guard_name = "*";

    #endregion

    #region Methods

    public function __construct(array $attributes = [])
    {
        $attributes['guard_name'] = $this->guard_name;
        parent::__construct($attributes);
    }

    /**
     * Return translated name.
     *
     * @return string
     */
    public function getTranslatedName(): string
    {
        return \App\Enums\Permission::getDescription($this->name);
    }

    #endregion
}
