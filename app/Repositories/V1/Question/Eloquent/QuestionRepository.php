<?php

namespace App\Repositories\V1\Question\Eloquent;

use App\Models\IntelligencePoint;
use App\Models\IntelligencePointQuestion;
use App\Models\Media;
use App\Models\Question;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Question\Interfaces\QuestionRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;

class QuestionRepository extends BaseRepository implements QuestionRepositoryInterface
{
    public function __construct(Question $model)
    {
        parent::__construct($model);
    }

    public function uploadFile($question, $file)
    {
        return $question->setDisk(Media::MEDIA_PUBLIC_DISK)
            ->setDirectory(Question::MEDIA_COLLECTION_QUESTIONS)
            ->setCollection(Question::MEDIA_COLLECTION_FILES)
            ->addMedia($file);
    }

    /**
     * @param $relation
     * @param $column
     * @return $this
     */
    public function withMaximum($relation, $column): static
    {
        $this->model = $this->model->withMax($relation, $column);
        return $this;
    }

    public function storeFiles($question, $data)
    {
        return $question->files()->create($data);
    }

    public function findOrFailFilesById($question, $id)
    {
        return $question->files()->findOrFail($id);
    }

    public function destroyFile($question, $mediaQuestion)
    {
        return $question->files()->where('id', $mediaQuestion)->delete();
    }

    public function resetFilesPriority($question, $ids)
    {
        $priority = 1;
        foreach ($ids as $id) {
            $question->files()->where('id', $id)->update(['priority' => $priority]);
            $priority++;
        }
    }

    /**
     * @return $this
     */
    public function withOrderExercisePointsSum(): static
    {
        $this->model = $this->model->with(['exercisePivotPoints' => function (HasManyDeep $hasManyDeep) {
            $hasManyDeep->selectRaw('SUM(max_point) as max_point_sum,intelligence_point_id')->groupBy('intelligence_point_id');
        }]);
        return $this;
    }

    /**
     * @return $this
     */
    public function withIntelligencePoints(): static
    {
        $this->model = $this->model->with(['intelligencePoints' => function (HasManyDeep $hasManyDeep) {
            $table = (new IntelligencePoint)->getTable();
            $hasManyDeep->select([$table . '.id', $table . '.intelligence_id', $table . '.max_point']);
        }]);
        return $this;
    }

    public function attachPoints($question, $points)
    {
        return $question->points()->attach($points);
    }

    public function detachPoints($question, $points)
    {
        return $question->points()->detach($points);
    }

    public function updatePoint($question, $point, $attributes = [])
    {
        return $question->points()->updateExistingPivot($point, $attributes);
    }

    /**
     * @param $question
     * @return int
     */
    public function getMaximumPriorityQuestion($question): int
    {
        return intval($question->answerTypes()->max('priority'));
    }

    public function paginateAnswerTypes($question, Request $request)
    {
        return $question->answerTypes()
            ->select(['id', 'question_id', 'type'])
            ->paginate($request->get('perPage', 10));
    }

    public function getAnswerTypes($question)
    {
        return $question->answerTypes()
            ->select(['id', 'question_id', 'type', 'priority'])
            ->get();
    }

    public function getPaginateAnswers(Request $request, $question)
    {
        return $question->answerRahjoos()
            ->with(['user:id,first_name,last_name,mobile','answers', 'answers.file'])->distinct()
            ->paginate($request->get('perPage', 10),['rahjoos.id','rahjoos.user_id']);
    }

}
