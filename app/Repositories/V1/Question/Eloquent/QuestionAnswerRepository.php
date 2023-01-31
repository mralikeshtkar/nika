<?php

namespace App\Repositories\V1\Question\Eloquent;

use App\Models\Media;
use App\Models\QuestionAnswer;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Question\Interfaces\QuestionAnswerRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class QuestionAnswerRepository extends BaseRepository implements QuestionAnswerRepositoryInterface
{

    public function __construct(QuestionAnswer $model)
    {
        parent::__construct($model);
    }

    public function uploadFile($questionAnswer, $file)
    {
        return $questionAnswer->setDisk(Media::MEDIA_PRIVATE_DISK)
            ->setDirectory(QuestionAnswer::MEDIA_DIRECTORY_QUESTION_ANSWERS)
            ->setCollection(QuestionAnswer::MEDIA_COLLECTION_QUESTION_ANSWERS)
            ->addMedia($file);
    }

}
