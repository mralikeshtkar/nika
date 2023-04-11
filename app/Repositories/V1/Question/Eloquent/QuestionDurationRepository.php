<?php

namespace App\Repositories\V1\Question\Eloquent;

use App\Models\QuestionDuration;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Question\Interfaces\QuestionDurationRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class QuestionDurationRepository extends BaseRepository implements QuestionDurationRepositoryInterface
{
    public function __construct(QuestionDuration $model)
    {
        parent::__construct($model);
    }

    /**
     * @param $conditions
     * @return Model|Relation|Builder
     */
    public function firstWhereOrFail($conditions): Model|Relation|Builder
    {
        return $this->model->where($conditions)->firstOrFail();
    }
}
