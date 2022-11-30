<?php

namespace App\Repositories\V1\Package\Eloquent;

use App\Models\Package;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Package\Interfaces\PackageRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PackageRepository extends BaseRepository implements PackageRepositoryInterface
{
    public function __construct(Package $model)
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
     * Filter pagination.
     *
     * @param Request $request
     * @return $this
     */
    public function filterPagination(Request $request): static
    {
        $this->model = $this->model->when($request->filled('title'), function (Builder $builder) use ($request) {
            $builder->where('title', '%' . $request->title . '%');
        });
        return $this;
    }

    /**
     * @param $package
     * @return void
     */
    public function destroyVideo($package)
    {
        if ($video = $package->video) $video->delete();
    }

}
