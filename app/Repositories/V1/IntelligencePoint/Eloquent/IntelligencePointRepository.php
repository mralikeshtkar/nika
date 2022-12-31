<?php

namespace App\Repositories\V1\IntelligencePoint\Eloquent;

use App\Models\IntelligencePoint;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\IntelligencePoint\Interfaces\IntelligencePointRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class IntelligencePointRepository extends BaseRepository implements IntelligencePointRepositoryInterface
{
    public function __construct(IntelligencePoint $model)
    {
        parent::__construct($model);
    }

    /**
     * @return $this
     */
    public function withPointName(): static
    {
        $this->model = $this->model->withPointName();
        return $this;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function filterPagination(Request $request): static
    {
        $this->model = $this->model->when($request->name, function (Builder $builder) use ($request) {
            $builder->whereHas('intelligencePointName', function (Builder $builder) use ($request) {
                $builder->where('name', 'LIKE', '%' . $request->name . '%');
            });
        });
        return $this;
    }
}
