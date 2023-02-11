<?php

namespace App\Repositories\V1\Question\Eloquent;

use App\Models\QuestionDuration;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Question\Interfaces\QuestionDurationRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class QuestionDurationRepository extends BaseRepository implements QuestionDurationRepositoryInterface
{
    public function __construct(QuestionDuration $model)
    {
        parent::__construct($model);
    }
}
