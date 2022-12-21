<?php

namespace App\Repositories\V1\IntelligencePointName\Eloquent;

use App\Models\IntelligencePointName;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\IntelligencePointName\Interfaces\IntelligencePointNameRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\HigherOrderWhenProxy;

class IntelligencePointNameRepository extends BaseRepository implements IntelligencePointNameRepositoryInterface
{
    public function __construct(IntelligencePointName $model)
    {
        parent::__construct($model);
    }

    public function update($model, array $attributes)
    {
        parent::update($model, $attributes);
        return $model->update();
    }

    /**
     * @return array|Collection
     */
    public function get(): array|Collection
    {
        return $this->model->get();
    }

    /**
     * @param Request $request
     * @return Builder|Model
     */
    public function filterPagination(Request $request): Model|Builder
    {
        $this->filterName($request->name);
        return $this->model;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function filterName($name): mixed
    {
        $this->model = $this->model->when($name, function (Builder $builder) use ($name) {
            $builder->where('name', 'LIKE', '%' . $name . '%');
        });
        return $this->model;
    }

}
