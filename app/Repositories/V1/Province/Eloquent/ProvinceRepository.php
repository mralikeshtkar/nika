<?php

namespace App\Repositories\V1\Province\Eloquent;

use App\Models\Province;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Province\Interfaces\ProvinceRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Pure;

class ProvinceRepository extends BaseRepository implements ProvinceRepositoryInterface
{
    /**
     * ProvinceRepository constructor.
     *
     * @param Province $model
     */
    #[Pure] public function __construct(Province $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all provinces.
     *
     * @return array|Collection
     */
    public function getAll(): array|Collection
    {
        return $this->model->get();
    }

    /**
     * Filter pagination
     *
     * @param Request $request
     * @return $this
     */
    public function filterPagination(Request $request): static
    {
        $this->model = $this->model->when($request->filled('name'), function (Builder $builder) use ($request) {
            $builder->where('name', 'LIKE', '%' . $request->name . '%');
        });
        return $this;
    }

    /**
     * Update a province.
     *
     * @param $model
     * @param array $attributes
     * @return mixed|void
     */
    public function update($model, array $attributes)
    {
        parent::update($model, $attributes);
        return $model->refresh();
    }


}
