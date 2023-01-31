<?php

namespace App\Repositories\V1\Exercise\Eloquent;

use App\Models\Exercise;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Exercise\Interfaces\ExerciseRepositoryInterfaces;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

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

    public function paginateQuestions(Request $request, $exercise)
    {
        return $exercise->questions()
            ->select(['id', 'exercise_id', 'title', 'created_at', 'updated_at'])
            ->with('files')
            ->paginate($request->get('perPage', 10));
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
