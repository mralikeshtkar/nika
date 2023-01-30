<?php

namespace App\Repositories\V1\Rahjoo\Eloquent;

use App\Models\Rahjoo;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Rahjoo\Interfaces\RahjooRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RahjooRepository extends BaseRepository implements RahjooRepositoryInterface
{
    public function __construct(Rahjoo $model)
    {
        parent::__construct($model);
    }

    /**
     * Update or create a personnel information.
     *
     * @param array $attributes
     * @param array $values
     * @return Model|Builder
     */
    public function updateOrCreate(array $attributes, array $values): Model|Builder
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    public function storeManyCourses($rahjoo, $courses, $user_id)
    {
        $courses = collect($courses)->map(function ($item) use ($user_id) {
            return [
                'user_id' => $user_id,
                'name' => $item['name'],
                'duration' => $item['duration'],
            ];
        });
        return $rahjoo->courses()->createMany($courses);
    }

    /**
     * @return $this
     */
    public function hasMotherOrFather(): static
    {
        $this->model = $this->model->where(function (Builder $builder) {
            $builder->whereHas('mother')->orWhereHas('father');
        });
        return $this;
    }

    public function updatePackage($rahjoo, $package)
    {
        return $rahjoo->update(['package_id'=>$package]);
    }
}
