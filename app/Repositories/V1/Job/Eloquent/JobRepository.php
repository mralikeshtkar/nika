<?php

namespace App\Repositories\V1\Job\Eloquent;

use App\Models\Job;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Job\Interfaces\JobRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Pure;

class JobRepository extends BaseRepository implements JobRepositoryInterface
{
    #[Pure] public function __construct(Job $model)
    {
        parent::__construct($model);
    }

    /**
     * @return array|Collection
     */
    public function all(): array|Collection
    {
        return $this->model->get();
    }

    /**
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
