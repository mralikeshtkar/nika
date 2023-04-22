<?php

namespace App\Repositories\V1\Rahjoo\Eloquent;

use App\Enums\Order\OrderStatus;
use App\Enums\Rahjoo\RahjooSupportStep;
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
        $this->searchHasPackage($request)
            ->searchName($request)
            ->searchHasAgent($request);
        return $this;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function searchHasAgent(Request $request): static
    {
        $this->model = $this->model->when($request->filled('has_agent'), function (Builder $builder) use ($request) {
            $builder->when($request->has_agent, function (Builder $builder) use ($request) {
                $builder->whereNotNull('agent_id');
            }, function (Builder $builder) use ($request) {
                $builder->whereNull('agent_id');
            });
        });
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

    public function updateQuestionPoints($rahjoo, $intelligence_point_id, $question, $point)
    {
        /** @var Rahjoo $rahjoo */
        $rahjoo->questionPoints()
            ->wherePivot('intelligence_point_id', $intelligence_point_id)
            ->updateExistingPivot($question, ['point' => $point]);
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function filterSupportStep(Request $request): static
    {
        $this->model->when($request->filled('step') && in_array($request->step, RahjooSupportStep::asArray()), function ($q) use ($request) {
            $q->whereHas('support', function ($q) use ($request) {
                $q->where('step', $request->step);
            });
        });
        return $this;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function filterPreparation(Request $request): static
    {
        $this->model->when($request->filled('preparation'), function ($q) use ($request) {
            $q->whereHas('orders', function ($q) use ($request) {
                $q->preparation();
            });
        });
        return $this;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function filterPosted(Request $request): static
    {
        $this->model->when($request->filled('posted'), function ($q) use ($request) {
            $q->whereHas('orders', function ($q) use ($request) {
                $q->posted()->orWhere(function ($q) {
                    $q->delivered()->where('is_used', false);
                });
            });
        });
        return $this;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function filterDelivered(Request $request): static
    {
        $this->model->when($request->filled('delivered'), function ($q) use ($request) {
            $q->whereHas('orders', function ($q) use ($request) {
                $q->delivered()->where('is_used', true);
            });
        });
        return $this;
    }

    /**
     * @param Request $request
     * @param $support
     * @return $this
     */
    public function filterCanceled(Request $request, $support): static
    {
        $this->model->whereHas('supports', function ($q) use ($request, $support) {
            $q->whereNotNull('canceled_at');
        });
        return $this;
    }

    public function storeIntelligenceRahnama($rahjoo, $rahnama_id, $intelligence_id)
    {
        /** @var Rahjoo $rahjoo */
        $intelligenceRahyab = $rahjoo->intelligenceRahnama()
            ->wherePivot('intelligence_id', $intelligence_id)
            ->pluck('id');
        if ($intelligenceRahyab->count())
            foreach ($intelligenceRahyab as $item) {
                $rahjoo->intelligenceRahnama()
                    ->wherePivot('intelligence_id', $intelligence_id)
                    ->detach($item);
            }
        $rahjoo->intelligenceRahnama()->attach($rahnama_id, ['intelligence_id' => $intelligence_id]);
    }

    public function updateSupport($rahjoo, $support_id)
    {
        return $rahjoo->update(['support_id' => $support_id]);
    }

    public function onlySupportRahjoos($support_id): static
    {
        $this->model = $this->model->where(function ($q) use ($support_id) {
            $q->whereHas('support', function ($q) use ($support_id) {
                $q->where('support_id', $support_id);
            })->has('support');
        });
        return $this;
    }

    public function withSupportIfIsSuperAdmin($user): static
    {
        $this->model = $this->model->when($user->isSuperAdmin(), function ($q) {
            $q->with(['support', 'support.support:id,first_name,last_name']);
        });
        return $this;
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
