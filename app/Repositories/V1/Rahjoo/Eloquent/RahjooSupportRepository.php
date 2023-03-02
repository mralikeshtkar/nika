<?php

namespace App\Repositories\V1\Rahjoo\Eloquent;

use App\Models\Rahjoo;
use App\Models\RahjooSupport;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Rahjoo\Interfaces\RahjooRepositoryInterface;
use App\Repositories\V1\Rahjoo\Interfaces\RahjooSupportRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RahjooSupportRepository extends BaseRepository implements RahjooSupportRepositoryInterface
{
    public function __construct(RahjooSupport $model)
    {
        parent::__construct($model);
    }

    /**
     * Update or create a personnel information.
     *
     * @param array $attributes
     * @param array $values
     * @return Model|Builder
     */
    public function updateOrCreate(array $attributes, array $values): Model|Builder
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

}
