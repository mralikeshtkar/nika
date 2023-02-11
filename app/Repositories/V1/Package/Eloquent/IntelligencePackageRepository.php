<?php

namespace App\Repositories\V1\Package\Eloquent;

use App\Models\IntelligencePackage;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Package\Interfaces\IntelligencePackageRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class IntelligencePackageRepository extends BaseRepository implements IntelligencePackageRepositoryInterface
{
    public function __construct(IntelligencePackage $model)
    {
        parent::__construct($model);
    }

    /**
     * @param $id
     * @return Model|array|Collection|Builder|null
     */
    public function findOrFailByPivotId($id): Model|array|Collection|Builder|null
    {
        return $this->model->where('pivot_id', $id)->firstOrFail();
    }

    /**
     * @param $package_id
     * @param $intelligence_id
     * @return Model|array|Collection|Builder|null
     */
    public function findOrFailByPackageAndIntelligenceId($package_id, $intelligence_id): Model|array|Collection|Builder|null
    {
        return $this->model->where(['package_id' => $package_id, 'intelligence_id' => $intelligence_id])->firstOrFail();
    }

    public function update($model, array $attributes)
    {
        return $model->where('pivot_id', $model->pivot_id)->update($attributes);
    }

    public function getPoints(Request $request, $intelligencePackage)
    {
        return $intelligencePackage->points()
            ->select(['id', 'intelligence_package_id', 'intelligence_point_name_id', 'max_point',])
            ->withPointName()
            ->when($request->filled('name'), function ($q) use ($request) {
                $q->whereHas('intelligencePointName', function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->name . '%');
                });
            })
            ->get();
    }

    public function getFeedbacks($intelligencePackage)
    {
        return $intelligencePackage->feedbacks()
            ->select(['id', 'intelligence_package_id', 'title', 'max_point'])
            ->get();
    }

    public function touch($intelligencePackage)
    {
        return $intelligencePackage->update(['updated_at' => now()]);
    }

    public function storeComment($intelligencePackage, $data)
    {
        return $intelligencePackage->comments()->create($data);
    }

}
