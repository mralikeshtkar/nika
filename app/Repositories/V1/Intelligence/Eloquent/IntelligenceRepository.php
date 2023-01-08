<?php

namespace App\Repositories\V1\Intelligence\Eloquent;

use App\Models\Intelligence;
use App\Models\IntelligencePackage;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Intelligence\Interfaces\IntelligenceRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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
            ->withPointName()
            ->get();
    }

    /**
     * @param $intelligence
     * @return mixed
     */
    public function getIntelligenceFeedbacks($intelligence): mixed
    {
        return $intelligence->feedbacks()
            ->select(["id", "intelligence_id", "title", "max_point"])
            ->get();
    }

    public function get(): array|Collection
    {
        return $this->model->get();
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
     * @param $intelligence
     * @param $points
     * @return mixed
     */
    public function createMultiplePoints($intelligence, $points): mixed
    {
        return $intelligence->points()->createMany(array_map(function ($item) {
            return $item + ['user_id' => optional(auth())->id()];
        }, $points));
    }

    /**
     * @param $intelligence
     * @param $feedbacks
     * @return mixed
     */
    public function createMultipleFeedbacks($intelligence, $feedbacks): mixed
    {
        return $intelligence->feedbacks()->createMany(array_map(function ($item) {
            return $item + ['user_id' => optional(auth())->id()];
        }, $feedbacks));
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
