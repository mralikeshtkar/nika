<?php

namespace App\Repositories\V1\Major\Eloquent;

use App\Models\Major;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Major\Interfaces\MajorRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Pure;

class MajorRepository extends BaseRepository implements MajorRepositoryInterface
{
    #[Pure] public function __construct(Major $model)
    {
        parent::__construct($model);
    }

    /**
     * Update a major.
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
     * Get all majors.
     *
     * @return array|Collection
     */
    public function all(): array|Collection
    {
        return $this->model->get();
    }

    /**
     * Filter pagination.
     *
     * @param Request $request
     * @return $this
     */
    public function filterPaginate(Request $request): static
    {
        $this->model = $this->model->when($request->filled('name'), function (Builder $builder) use ($request) {
            $builder->where('name', 'LIKE', '%' . $request->name . '%');
        });
        return $this;
    }

}
