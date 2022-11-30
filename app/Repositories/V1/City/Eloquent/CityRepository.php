<?php

namespace App\Repositories\V1\City\Eloquent;

use App\Models\City;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\City\Interfaces\CityRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Pure;

class CityRepository extends BaseRepository implements CityRepositoryInterface
{
    /**
     * CityRepository constructor.
     *
     * @param City $model
     */
    #[Pure] public function __construct(City $model)
    {
        parent::__construct($model);
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
            $builder->where('name', 'LIKE', '%' . $request->name . '%');
        })->when($request->filled('province'), function (Builder $builder) use ($request) {
            $builder->orWhereHas('province', function (Builder $builder) use ($request) {
                $builder->where('name', 'LIKE', '%' . $request->province . '%');
            });
        });
        return $this;
    }

    /**
     * Add aggregate province name.
     *
     * @return $this
     */
    public function withProvinceName(): static
    {
        $this->model = $this->model->withAggregate('province', 'name');
        return $this;
    }

    /**
     * Create a city.
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model
    {
        $city = parent::create($attributes);
        return $city->loadAggregate('province', 'name');
    }

    /**
     * Update a city.
     *
     * @param $model
     * @param array $attributes
     * @return mixed|void
     */
    public function update($model, array $attributes)
    {
        parent::update($model, $attributes);
        return $model->loadAggregate('province', 'name');
    }


}
