<?php

namespace App\Repositories\V1\Rahjoo\Eloquent;

use App\Models\Rahjoo;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Rahjoo\Interfaces\RahjooRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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

    /**
     * @param Request $request
     * @return $this
     */
    public function haveNotRahnamaRahyab(Request $request): static
    {
        $this->model = $this->model->where(function (Builder $builder) {
            $builder->whereNull('rahnama_id')
                ->orWhereNull('rahyab_id');
        });
        return $this;
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
     * @param Request $request
     * @return $this
     */
    public function filterPagination(Request $request): static
    {
        $this->searchHasPackage($request)->searchName($request);
        return $this;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function searchHasPackage(Request $request): static
    {
        $this->model = $this->model->when($request->filled('has_package'), function (Builder $builder) use ($request) {
            $builder->when($request->has_package, function (Builder $builder) use ($request) {
                $builder->whereNotNull('package_id');
            }, function (Builder $builder) use ($request) {
                $builder->whereNull('package_id');
            });
        });
        return $this;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function searchName(Request $request): static
    {
        $this->model = $this->model->when($request->filled('name'), function (Builder $builder) use ($request) {
            $builder->where(function (Builder $builder) use ($request) {
                $builder->whereHas('user', function (Builder $builder) use ($request) {
                    $builder->where('first_name', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $request->name . '%');
                });
            });
        });
        return $this;
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
        return $rahjoo->update(['package_id' => $package]);
    }

    public function attachQuestionPoints($rahjoo, $points)
    {
        $rahjoo->questionPoints()->attach($points);
    }

    public function updateQuestionPoints($rahjoo, $question, $point)
    {
        /** @var Rahjoo $rahjoo */
        $rahjoo->questionPoints()->updateExistingPivot($question, ['point' => $point]);
    }

    public function storeIntelligenceRahnama($rahjoo, $rahnama_id, $intelligence_id)
    {
        /** @var Rahjoo $rahjoo */
//        $intelligenceRahyab = $rahjoo->intelligenceRahyab()->wherePivot('intelligence_id',$intelligence_id)->pluck('id');
//        if ($intelligenceRahyab->count())
        /*if ($intelligenceRahyab->count())
        $rahjoo->intelligenceRahyab()->wherePivot('intelligence_id',$intelligence_id)->delete();*/
        $rahjoo->intelligenceRahnama()->attach($rahnama_id, ['intelligence_id' => $intelligence_id]);
    }

    public function attachIntelligencePackagePoints($rahjoo, $intelligencePackagePoints, $points)
    {
        /** @var Rahjoo $rahjoo */
        foreach ($points as $intelligence_point_id => $point) {
            if ($intelligencePackagePoints->where('intelligence_package_id', $point['intelligence_package_id'])
                ->where('rahjoo_id', $rahjoo->id)
                ->where('intelligence_point_id', $intelligence_point_id)
                ->count()) {
                $rahjoo->intelligencePackagePoints()
                    ->wherePivot('rahjoo_id', $rahjoo->id)
                    ->wherePivot('intelligence_point_id', $intelligence_point_id)
                    ->updateExistingPivot($point['intelligence_package_id'], ['point' => $point['point']]);
            } else {
                $rahjoo->intelligencePackagePoints()->attach($point['intelligence_package_id'], [
                    'rahjoo_id' => $rahjoo->id,
                    'user_id' => $point['user_id'],
                    'point' => $point['point'],
                    'intelligence_point_id' => $intelligence_point_id,
                ]);
            }
        }
    }
}
