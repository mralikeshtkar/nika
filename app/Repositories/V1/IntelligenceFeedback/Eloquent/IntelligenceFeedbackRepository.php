<?php

namespace App\Repositories\V1\IntelligenceFeedback\Eloquent;

use App\Models\IntelligenceFeedback;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\IntelligenceFeedback\Interfaces\IntelligenceFeedbackRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class IntelligenceFeedbackRepository extends BaseRepository implements IntelligenceFeedbackRepositoryInterface
{
    public function __construct(IntelligenceFeedback $model)
    {
        parent::__construct($model);
    }

    public function update($model, array $attributes)
    {
        parent::update($model, $attributes);
        return $model->refresh();
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

}
