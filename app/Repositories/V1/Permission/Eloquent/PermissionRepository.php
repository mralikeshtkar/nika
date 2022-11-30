<?php

namespace App\Repositories\V1\Permission\Eloquent;

use App\Models\Permission;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Permission\Interfaces\PermissionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    /**
     * PermissionRepository constructor.
     *
     * @param Permission $model
     */
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all permissions.
     *
     * @return array|Collection
     */
    public function getAll(): array|Collection
    {
        return $this->model->get();
    }
}
