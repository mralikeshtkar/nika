<?php

namespace App\Repositories\V1\Package\Eloquent;

use App\Models\Media;
use App\Models\Package;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Package\Interfaces\PackageRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PackageRepository extends BaseRepository implements PackageRepositoryInterface
{
    public function __construct(Package $model)
    {
        parent::__construct($model);
    }

    public function findOrFailIntelligenceByIntelligences($package, $intelligence, $columns = ['*'])
    {
        return $package->intelligences()->select($columns)->findOrFail($intelligence);
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
     * @param $video
     * @return null
     */
    public function uploadVideo($package, $video)
    {
        return $video ? $package->setDisk(Media::MEDIA_PUBLIC_DISK)
            ->setDirectory(Package::MEDIA_DIRECTORY_VIDEOS)
            ->setCollection(Package::MEDIA_COLLECTION_VIDEO)
            ->addMedia($video) : null;
    }

    /**
     * @param $package
     * @param $intelligences
     * @return mixed
     */
    public function syncIntelligences($package, $intelligences): mixed
    {
        return $package->intelligences()
            ->withTimestamps()
            ->sync($intelligences);
    }

    /**
     * @param $package
     * @param $intelligences
     * @return mixed
     */
    public function attachIntelligences($package, $intelligences): mixed
    {
        return $package->intelligences()
            ->withTimestamps()
            ->attach($intelligences);
    }

    public function getPackageIntelligences($package, $request)
    {
        return $package->intelligences()
            ->withTimestamps()
            ->paginate($request->get('perPage', 10), ['id', 'title']);
    }

    public function findIntelligenceOrFailById($package, $intelligence, array $columns = ['*'])
    {
        return $package->intelligences()->select($columns)->findOrFail($intelligence);
    }

    public function intelligenceCompleted($package, $intelligence)
    {
        return $package->intelligences()
            ->withTimestamps()
            ->sync([
                $intelligence => ['is_completed' => true],
            ]);
    }

    public function intelligenceUncompleted($package, $intelligence)
    {
        return $package->intelligences()
            ->withTimestamps()
            ->sync([
                $intelligence => ['is_completed' => false],
            ]);
    }

    public function detachIntelligences($package, $intelligences)
    {
        return $package->intelligences()->detach($intelligences);
    }

    public function completed($package)
    {
        return $package->update(['is_completed' => true]);
    }

    public function uncompleted($package)
    {
        return $package->update(['is_completed' => false]);
    }

    public function changeStatus($package, $status)
    {
        return $package->update(['status' => $status]);
    }

    /**
     * @param $package
     * @return void
     */
    public function destroyVideo($package)
    {
        if ($video = $package->video) $video->delete();
    }

    public function storeExercisePriority($package, $data)
    {
        return $package->exercisePriority()->attach(collect($data)->mapWithKeys(function ($item, $key) use ($package) {
            return [
                $item['exercise_id'] => [
                    'intelligence_id' => $item['intelligence_id'],
                    'priority' => intval($package->pivotExercisePriority()->max('priority')) + 1,
                ],
            ];
        })->toArray());
    }

    public function destroyExercisePriority($package, $ids)
    {
        return $package->exercisePriority()->detach($ids);
    }

    public function getPaginateQuestions(Request $request, $package)
    {
        return $package->questions()->paginate($request->get('perPage'));
    }

    /**
     * @param Request $request
     * @param $package
     * @param $rahjoo
     * @return LengthAwarePaginator
     */
    public function getPaginateExercises(Request $request, $package, $rahjoo): LengthAwarePaginator
    {
        $ids = $rahjoo->package->pivotExercisePriority->pluck('exercise_id')->reverse();
        /** @var Package $package */
        return $package->exercises()
            ->has('questions')
            ->orderByRaw(DB::raw("FIELD(id, " . $ids->implode(', ') . ") DESC"))
            ->when($request->filled('lock'), function (Builder $builder) use ($request) {
                $builder->when($request->lock == "locked", function (Builder $builder) use ($request) {
                    $builder->locked();
                })->when($request->lock == "notlocked", function (Builder $builder) use ($request) {
                    $builder->notLocked();
                });
            })->paginate($request->get('perPage'));
    }

    public function findPackageExerciseById(Request $request, $package, $exercise)
    {
        return $package->exercises()->findOrFail($exercise);
    }

    public function paginateExercises(Request $request, $package)
    {
        return $package->exercises()
            ->when($package->relationLoaded('pivotExercisePriority'), function (Builder $builder) use ($package) {
                $ids = $package->pivotExercisePriority->pluck('exercise_id')->reverse();
                $builder->orderByRaw(DB::raw("FIELD(id, " . $ids->implode(', ') . ") DESC"));
            })->when($request->filled('lock'), function (Builder $builder) use ($request) {
                $builder->when($request->lock == "locked", function (Builder $builder) use ($request) {
                    $builder->locked();
                })->when($request->lock == "notlocked", function (Builder $builder) use ($request) {
                    $builder->notLocked();
                });
            })->paginate($request->get('perPage'));
    }

    /**
     * @param Request $request
     * @param $exercise_ids
     * @return $this
     */
    public function pivotIntelligencePackageHasExercise(Request $request, $exercise_ids): static
    {
        $this->model = $this->model->withWhereHas('pivotIntelligencePackage',function ($q) use ($exercise_ids){
            $q->withWhereHas('exercise',function ($q) use ($exercise_ids){
                $q->whereNotIn('id', $exercise_ids->toArray());
            });
        });
        return $this;
    }

}
