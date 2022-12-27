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
     * @param $intelligence
     * @return mixed
     */
    public function getIntelligencePoints($intelligence): mixed
    {
        return $intelligence->points()
            ->select(["id", "intelligence_id", "intelligence_point_name_id", "package_id", "max_point"])
            ->get();
    }

    /**
     * @param $intelligence
     * @param $points
     * @return mixed
     */
    public function createMultiplePoints($intelligence, $points): mixed
    {
        return $intelligence->points()->createMany(array_map(function ($item) {
            return $item + ['user_id' => optional(auth())->id()];
        },$points));
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
