<?php

namespace App\Observers\V1\Question;

use App\Models\Question;
use App\Repositories\V1\Media\Interfaces\MediaRepositoryInterface;

class QuestionObserver
{
    /**
     * @param Question $question
     * @return void
     */
    public function deleted(Question $question)
    {
        resolve(MediaRepositoryInterface::class)->destroyAll($question->files);
    }
}
