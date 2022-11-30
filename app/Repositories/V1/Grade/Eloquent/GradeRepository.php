<?php

namespace App\Repositories\V1\Grade\Eloquent;

use App\Models\Grade;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\City\Eloquent\CityRepository;
use App\Repositories\V1\Grade\Interfaces\GradeRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Pure;

class GradeRepository extends BaseRepository implements GradeRepositoryInterface
{
    /**
     * GradeRepository constructor.
     *
     * @param Grade $model
     */
    #[Pure] public function __construct(Grade $model)
    {
        parent::__construct($model);
    }

    /**
     * Update a grade.
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
        });
        return $this;
    }
}
