<?php

namespace App\Repositories\V1\Personnel\Eloquent;

use App\Models\Personnel;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Personnel\Interfaces\PersonnelRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use JetBrains\PhpStorm\Pure;

class PersonnelRepository extends BaseRepository implements PersonnelRepositoryInterface
{
    /**
     * PersonnelRepository constructor.
     *
     * @param Personnel $model
     */
    #[Pure] public function __construct(Personnel $model)
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
