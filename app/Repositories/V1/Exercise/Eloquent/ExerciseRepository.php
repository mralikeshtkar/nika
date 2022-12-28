<?php

namespace App\Repositories\V1\Exercise\Eloquent;

use App\Models\Exercise;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Exercise\Interfaces\ExerciseRepositoryInterfaces;
use Illuminate\Database\Eloquent\Model;

class ExerciseRepository extends BaseRepository implements ExerciseRepositoryInterfaces
{
    public function __construct(Exercise $model)
    {
        parent::__construct($model);
    }
}
