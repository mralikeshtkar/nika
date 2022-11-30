<?php

namespace App\Repositories\V1\RahjooCourse\Eloquent;

use App\Models\RahjooCourse;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\RahjooCourse\Interfaces\RahjooCourseRepositoryInterface;

class RahjooCourseRepository extends BaseRepository implements RahjooCourseRepositoryInterface
{
    public function __construct(RahjooCourse $model)
    {
        parent::__construct($model);
    }
}
