<?php

namespace App\Repositories\V1\DocumentGroup\Eloquent;

use App\Models\DocumentGroup;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\DocumentGroup\Interfaces\DocumentGroupRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class DocumentGroupRepository extends BaseRepository implements DocumentGroupRepositoryInterface
{
    public function __construct(DocumentGroup $model)
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
        $this->model = $this->model->when($request->title, function (Builder $builder) use ($request) {
            $builder->where('title', 'LIKE', '%' . $request->title . '%');
        });
        return $this;
    }

}
