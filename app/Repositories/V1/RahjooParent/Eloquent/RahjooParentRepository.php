<?php

namespace App\Repositories\V1\RahjooParent\Eloquent;

use App\Models\RahjooParent;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\RahjooParent\Interfaces\RahjooParentRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RahjooParentRepository extends BaseRepository implements RahjooParentRepositoryInterface
{
    public function __construct(RahjooParent $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $attributes
     * @param array $values
     * @return Model|Builder
     */
    public function updateOrCreate(array $attributes, array $values): Model|Builder
    {
        return $this->model->updateOrCreate($attributes, $values);
    }
}
