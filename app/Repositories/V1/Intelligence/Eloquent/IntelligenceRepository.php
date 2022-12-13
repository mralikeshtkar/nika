<?php

namespace App\Repositories\V1\Intelligence\Eloquent;

use App\Models\Intelligence;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Intelligence\Interfaces\IntelligenceRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class IntelligenceRepository extends BaseRepository implements IntelligenceRepositoryInterface
{
    public function __construct(Intelligence $model)
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
