<?php

namespace App\Repositories\V1;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class BaseRepository implements EloquentRepositoryInterface
{
    protected array $scopes = [];

    /**
     * @var Model|Builder|Relation
     */
    protected Model|Builder|Relation $model;

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
     * @param $column
     * @return $this
     */
    public function whereNull($column): static
    {
        $this->model->whereNull($column);
        return $this;
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
     * @param $query
     * @return $this
     */
    public function query($query): static
    {
        $this->model = $query;
        return $this;
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
     * @return $this
     */
    public function latest(): static
    {
        $this->model->latest();
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

    /**
     * @param array $scopes
     * @return $this
     */
    public function withScopes(array $scopes): static
    {
        $this->model = $this->model->scopes($scopes);
        return $this;
    }

    /**
     * @param $relations
     * @return $this
     */
    public function withCount($relations): static
    {
        $this->model->withCount($relations);
        return $this;
    }

    /**
     * @param string $column
     * @param $value
     * @return $this
     */
    public function where(string $column, $value): static
    {
        $this->model = $this->model->where($column,$value);
        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function whereNotNull(string $column): static
    {
        $this->model = $this->model->whereNotNull($column);
        return $this;
    }

    /**
     * @return array|Collection
     */
    public function get(): array|Collection
    {
        return $this->model->get();
    }

    public function firstOrCreate($attributes,$values): Model|Relation|Builder
    {
        return $this->model->firstOrCreate($attributes,$values);
    }

}
