<?php

namespace App\Repositories\V1\Exercise\Eloquent;

use App\Models\Exercise;
use App\Models\QuestionAnswer;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Exercise\Interfaces\ExerciseRepositoryInterfaces;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExerciseRepository extends BaseRepository implements ExerciseRepositoryInterfaces
{
    public function __construct(Exercise $model)
    {
        parent::__construct($model);
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function filterPagination(Request $request): static
    {
        $this->model = $this->model->when($request->filled('title'), function (Builder $builder) use ($request) {
            $builder->where('title', 'LIKE', '%' . $request->title . '%');
        });
        return $this;
    }

    /**
     * @param $ids
     * @return $this
     */
    public function whereIntelligencePackageId(...$ids): static
    {
        $this->model = $this->model->whereIn('intelligence_package_id', func_get_args());
        return $this;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function searchTitle(Request $request): static
    {
        $this->model = $this->model->when($request->filled('title'), function (Builder $builder) use ($request) {
            $builder->where('title', 'LIKE', '%' . $request->title . '%');
        });
        return $this;
    }

    /**
     * @param $exercise
     * @return int
     */
    public function getMaximumQuestionsPriority($exercise): int
    {
        return intval($exercise->questions()->max('priority'));
    }

    public function resetQuestionPriorities($exercise, $ids)
    {
        $priority = 1;
        foreach ($ids as $id) {
            $exercise->questions()->where('id', $id)->update(['update' => $id]);
            $priority++;
        }
    }

    /**
     * @param Request $request
     * @param $exercise
     * @param $rahjoo
     * @return LengthAwarePaginator
     */
    public function paginateQuestions(Request $request, $exercise, $rahjoo = null): LengthAwarePaginator
    {
        /** @var Exercise $exercise */
        return $exercise->questions()
            ->select(['id', 'exercise_id', 'title', 'created_at', 'updated_at'])
            ->with(['files', 'files.media', 'answerTypes:id,question_id,type'])
            ->when($rahjoo, function (Builder $builder) use ($rahjoo) {
                $builder->addSelect(['rahjoo_answers_count' => QuestionAnswer::query()->selectRaw('COUNT(*)')
                    ->where('question_answers.rahjoo_id', $rahjoo)
                    ->whereColumn('questions.id', '=', 'question_answers.question_id'),
                ]);
            })->paginate($request->get('perPage', 10));
    }

    public function findSingleQuestion(Request $request, $exercise, $question, $rahjoo = null): Model|Collection|HasMany|array|null
    {
        /** @var Exercise $exercise */
        return $exercise->questions()
            ->select(['id', 'exercise_id', 'title', 'created_at', 'updated_at'])
            ->with(['files', 'files.media', 'answerTypes:id,question_id,type'])
            ->withCount(['answerTypes', 'answers' => function ($q) use ($rahjoo) {
                $q->where('rahjoo_id', $rahjoo);
            }])->having('answers_count', '!=', DB::raw('answer_types_count'))
            ->findOrFail($question);
    }

    public function findExerciseQuestionById(Request $request, $exercise, $question, array $columns = ['id', 'exercise_id', 'title', 'created_at', 'updated_at'], array $relations = ['files'])
    {
        return $exercise->questions()
            ->select($columns)
            ->with($relations)
            ->findOrFail($question);
    }

    public function lock($exercise)
    {
        return $exercise->update(['is_locked' => true]);
    }

    public function unlock($exercise)
    {
        return $exercise->update(['is_locked' => false]);
    }
}
