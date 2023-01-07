<?php

namespace App\Repositories\V1\Package\Eloquent;

use App\Models\Media;
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

}
