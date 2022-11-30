<?php

namespace App\Repositories\V1\Skill\Eloquent;

use App\Models\Skill;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Skill\Interfaces\SkillRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Pure;

class SkillRepository extends BaseRepository implements SkillRepositoryInterface
{
    #[Pure] public function __construct(Skill $model)
    {
        parent::__construct($model);
    }

    /**
     * @param $model
     * @param array $attributes
     * @return mixed|void
     */
    public function update($model, array $attributes)
    {
        parent::update($model, $attributes);
        return $model->refresh();
    }

    /**
     * Add filter to pagination.
     *
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
     * @return array|Collection
     */
    public function get(): array|Collection
    {
        return $this->model->get();
    }

}
