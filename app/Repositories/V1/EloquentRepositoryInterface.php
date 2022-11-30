<?php

namespace App\Repositories\V1;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface EloquentRepositoryInterface
{
    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model;

    /**
     * @param $model
     * @param array $attributes
     * @return mixed|void
     */
    public function update($model, array $attributes);

    /**
     * @param $model
     * @return void
     */
    public function destroy($model);

    /**
     * @param array $relations
     * @return $this
     */
    public function with(array $relations): static;

    /**
     * @param array $columns
     * @return $this
     */
    public function select(array $columns): static;

    /**
     * @param $id
     * @return Model|array|Collection|Builder|null
     */
    public function findOrFailById($id): Model|array|Collection|Builder|null;

    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage): LengthAwarePaginator;
}
