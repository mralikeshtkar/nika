<?php

namespace App\Repositories\V1\Question\Eloquent;

use App\Models\QuestionAnswerType;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Question\Interfaces\QuestionAnswerTypeServiceRepositoryInterface;

class QuestionAnswerTypeServiceRepository extends BaseRepository implements QuestionAnswerTypeServiceRepositoryInterface
{
    public function __construct(QuestionAnswerType $model)
    {
        parent::__construct($model);
    }

    public function resetAnswerTypesPriority($question, $ids)
    {
        $priority = 1;
        foreach ($ids as $id) {
            $question->answerTypes()
                ->where('id', $id)
                ->update(['priority' => $priority]);
            $priority++;
        }
    }
}
