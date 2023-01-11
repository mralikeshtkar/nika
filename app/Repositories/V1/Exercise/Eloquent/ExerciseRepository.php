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
    public function whereIntelligencePackageId(... $ids): static
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

    public function paginateQuestions(Request $request, $exercise)
    {
        return $exercise->questions()
            ->select(['id', 'exercise_id', 'title'])
            ->with('files')
            ->paginate($request->get('perPage', 10));
    }
}
