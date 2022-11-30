<?php

namespace App\Repositories\V1;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements EloquentRepositoryInterface
{
    /**
     * @var Model|Builder
     */
    protected Model|Builder $model;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
     * @param $model
     * @param array $attributes
     * @return mixed|void
     */
    public function update($model, array $attributes)
    {
        $model->update($attributes);
    }

    /**
     * @param $model
     * @return void
     */
    public function destroy($model)
    {
        $model->delete();
    }

    /**
     * @param $id
     * @return Model|array|Collection|Builder|null
     */
    public function findOrFailById($id): Model|array|Collection|Builder|null
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Add eager relations.
     *
     * @param array $relations
     * @return $this
     */
    public function with(array $relations): static
    {
        $this->model = $this->model->with($relations);
        return $this;
    }

    /**
     * Add select columns.
     *
     * @param array $columns
     * @return $this
     */
    public function select(array $columns): static
    {
        $this->model = $this->model->select($columns);
        return $this;
    }

    /**
     * Return data as pagination.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }
}
