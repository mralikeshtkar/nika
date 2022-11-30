<?php

namespace App\Repositories\V1\Role\Eloquent;

use App\Models\Role;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Role\Interfaces\RoleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Pure;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    /**
     * @param Role $model
     */
    #[Pure] public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    /**
     * Find a role or fail by id.
     *
     * @param $role
     * @return Model|array|Collection|Builder|null
     */
    public function findOrFailById($role): Model|array|Collection|Builder|null
    {
        return $this->model->findOrFail($role);
    }

    /**
     * Get all roles.
     *
     * @return array|Collection
     */
    public function getAll(): array|Collection
    {
        return $this->model->get();
    }

    /**
     * Sync role permissions.
     *
     * @param $role
     * @param array $permissions
     * @return void
     */
    public function syncPermissions($role, array $permissions)
    {
        $role->syncPermissions($permissions);
    }

    /**
     * Filter pagination.
     *
     * @param Request $request
     * @return $this
     */
    public function filterPagination(Request $request): static
    {
        $this->model = $this->model->when($request->filled('name'), function (Builder $builder) use ($request) {
            $builder->where('name', 'LIKE', '%' . $request->name . '%')
                ->orWhere('name_fa', 'LIKE', '%' . $request->name . '%');
        });
        return $this;
    }

}
