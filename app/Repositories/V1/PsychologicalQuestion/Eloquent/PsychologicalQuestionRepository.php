<?php

namespace App\Repositories\V1\PsychologicalQuestion\Eloquent;

use App\Models\PsychologicalQuestion;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\PsychologicalQuestion\Interfaces\PsychologicalQuestionRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use JetBrains\PhpStorm\Pure;

class PsychologicalQuestionRepository extends BaseRepository implements PsychologicalQuestionRepositoryInterface
{
    #[Pure] public function __construct(PsychologicalQuestion $model)
    {
        parent::__construct($model);
    }

    /**
     * Update or create a psychological question.
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
